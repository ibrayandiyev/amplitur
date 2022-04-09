<?php

namespace App\Repositories;

use App\Enums\Clients\ClientSearchFor;
use App\Enums\PersonType;
use App\Events\ClientCreatedEvent;
use App\Events\ClientUpdatedEvent;
use App\Exports\ClientsExport;
use App\Mail\ClientPasswordChanged;
use App\Mail\ClientRecoveryPassword;
use App\Mail\ClientRecoveryUsername;
use App\Models\Client;
use App\Repositories\Concerns\ActionExport;
use App\Repositories\Traits\HasAddressContact;
use App\Repositories\Traits\HasPassword;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use InvalidArgumentException;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Collection as SupportCollection;


class ClientRepository extends Repository
{
    use HasAddressContact,
        HasPassword,
        ActionExport;

    public function __construct(Client $model)
    {
        $this->model = $model;
    }

    /**
     * Filter a resource
     *
     * @param   array  $id
     *
     * @return  Model|Collection|null
     */
    public function filter(array $params, ?int $limit = null): SupportCollection
    {
        $builder = $this->model->query(); 

        if(isset($params["search_for"]) && isset($params["wildcard"]) && $params["wildcard"] != ""){
            $params[$params["search_for"]] = $params["wildcard"];
            unset($params["search_for"]);
            unset($params["wildcard"]);
        }
        return parent::filter($params, $limit);
    }

    /**
     * [listPerson description]
     *
     * @return  [type]  [return description]
     */
    public function listPerson()
    {
        $clients = $this->model
            ->where('type', PersonType::FISICAL)
            ->get();

        return $clients;
    }

    /**
     * @inherited
     */
    public function onBeforeStore(array $attributes): array
    {
        $attributes = $this->handlePassword($attributes);

        if (isset($attributes['birthdate']) && !empty($attributes['birthdate'])) {
            $attributes['birthdate'] = $this->parseBirthdate($attributes['birthdate']);
        }

        if (isset($attributes['registry']) && !empty($attributes['registry'])) {
            $attributes['registry'] = $this->parseTaxvat($attributes['registry']);
        }

        return $attributes;
    }

    /**
     * @inhreted
     */
    public function onAfterStore(Model $resource, array $attributes): Model
    {
        $resource = parent::onAfterStore($resource, $attributes);
        $resource->generateValidationToken();
        $resource->save();
        
        $this->handleContacts($resource, $attributes['contacts']);
        $this->handleAddress($resource, $attributes['address']);

        $this->requestVerification($resource);
        
        ClientCreatedEvent::dispatch($resource->fresh());

        return $resource;
    }

    /**
     * @inherited
     */
    public function onBeforeUpdate(Model $resource, array $attributes): array
    {
        $attributes = $this->handlePassword($attributes);

        if (isset($attributes['birthdate']) && !empty($attributes['birthdate'])) {
            $attributes['birthdate'] = $this->parseBirthdate($attributes['birthdate']);
        }

        return $attributes;
    }

    /**
     * @inherited
     */
    public function onAfterUpdate(Model $resource, array $attributes): Model
    {
        $resource = parent::onAfterStore($resource, $attributes);

        if(isset($attributes['contacts'])){
            $this->handleContacts($resource, $attributes['contacts']);
        }
        if(isset($attributes['address'])){
            $this->handleAddress($resource, $attributes['address']);
        }

        ClientUpdatedEvent::dispatch($resource->fresh());

        return $resource;
    }

    /**
     * @inherited
     */
    public function onBeforeFilter(Builder $builder, array $params): Builder
    {
        $wheres = $builder->getQuery()->wheres;

        foreach ($wheres as $i => $where) {
            foreach ($where as $key => $value) {
                if ($where[$key] != 'country') {
                    continue;
                }

                if ($wheres[$i]['value'] != 'other') {
                    $wheres[$i]['operator'] = '!=';
                    $wheres[$i]['value'] = 'BR';
                    $builder->getQuery()->bindings['where'][$i] = 'BR';
                }
            }
        }
        

        $builder->getQuery()->wheres = $wheres;

        if(isset($params["start_date"])){
            $builder = $builder->whereDate("created_at", ">=", convertDate($params["start_date"]));
        }
        if(isset($params["end_date"])){
            $builder = $builder->whereDate("created_at", "<=", convertDate($params["end_date"]));
        }
        
        return $builder;
    }

    /**
     * @inherited
     */
    public function onBeforeListFilter($model)
    {
        $model = $model->orderBy("created_at", "DESC");
        return $model;
    }


    public function download($params = null)
    {
        $hash = time();

        return Excel::download(new ClientsExport($params), "CLIENTS_{$hash}.csv");
    }

    public function findByEmail(string $email): ?Client
    {
        $client = $this->model->where('email', $email)->first();

        return $client;
    }

    public function findByTokens($rememberToken=null, $verificationToken=null): ?Client
    {
        $client = $this->model
            ->where('remember_token'    , $rememberToken)
            ->where('verification_token', $verificationToken)
            ->first();

        return $client;
    }

    /**
     * [parseBirthdate description]
     *
     * @param   string  $birthdate  [$birthdate description]
     *
     * @return  [type]              [return description]
     */
    public function parseBirthdate(string $birthdate)
    {
        $formats = ['Y-m-d', 'd/m/Y', 'Y/m/d'];
        $date = null;

        foreach ($formats as $format) {
            try {
                if ($date = Carbon::createFromFormat($format, $birthdate)) {
                    break;
                }
            } catch (InvalidArgumentException $ex) {
                // Do nothing
            }
        }

        if (empty($date)) {
            throw new Exception("Invalid birthdate format");
        }

        return $date->format('Y-m-d');
    }

    /**
     * [parseTaxvat description]
     *
     * @param   string  $birthdate  [$birthdate description]
     *
     * @return  [type]              [return description]
     */
    public function parseTaxvat(string $taxvat)
    {
        $taxvat  = preg_replace("?[\/,.\\\-]*?", "", $taxvat);

        return $taxvat;
    }

    /**
     * [recoveryPassword description]
     *
     * @param   string  $email  [$email description]
     *
     * @return  bool            [return description]
     */
    public function recoveryPassword(string $email): bool
    {
        $client = $this->findByEmail($email);

        if (empty($client)) {
            return false;;
        }

        $token                      = md5($client->id . $client->username . date("Y-m-d"));

        $client->remember_token     = $_data['token'] = $token;
        $client->verification_token = \Str::random(64);

        $client->save();
        $client->fresh();

        Mail::to($client->email)->send(new ClientRecoveryPassword($client, $_data));

        return true;
    }

    /**
     * [doChangePassword description]
     *
     * @param   arry  $_data  [$_data description]
     *
     * @return  bool            [return description]
     */
    public function doChangePassword($_data=null): bool
    {
        $client = $this->findByTokens($_data['token'], $_data['verification_token']);

        if (empty($client)) {
            return false;;
        }

        $client->password           = Hash::make($_data['password']);
        $client->verification_token = null;
        $client->remember_token     = null;

        $client->save();
        $client->fresh();

        Mail::to($client->email)->send(new ClientPasswordChanged($client));

        return true;
    }

    /**
     * [recoveryPassword description]
     *
     * @param   string  $email  [$email description]
     *
     * @return  bool            [return description]
     */
    public function validateRecoveryToken($email, $validateToken=null): bool
    {
        $client = $this->findByEmail($email);

        if (empty($client)) {
            return false;;
        }

        $token  = md5($client->id . $client->username . date("Y-m-d"));

        return $token == $validateToken;
    }

    /**
     * [recoveryUsername description]
     *
     * @param   string  $email  [$email description]
     *
     * @return  bool            [return description]
     */
    public function recoveryUsername(string $email): bool
    {
        $client = $this->findByEmail($email);

        if (empty($client)) {
            return false;
        }

        Mail::to($client->email)->send(new ClientRecoveryUsername($client));

        return true;
    }

    /**
     * [requestVerification description]
     *
     * @param   Client  $client  [$client description]
     *
     * @return  bool             [return description]
     */
    public function requestVerification(Client $client): bool
    {
        $client->verification_token = \Str::random(64);
        $client->verified_at = null;
        $client->save();

        return true;
    }

    /**
     * [verifyAccount description]
     *
     * @param   string  $token  [$token description]
     *
     * @return  Client          [return description]
     */
    public function verifyAccount(string $token): ?Client
    {
        $client = $this->model->where('verification_token', $token)->first();

        if (empty($client) || !empty($client->verified_at)) {
            return null;
        }

        $client->verified_at = Carbon::now();
        $client->save();

        return $client->fresh();
    }

    /**
     * [validateToken description]
     *
     * @param   array     $attributes  [$attributes description]
     *
     * @return  Client               [return description]
     */
    public function validateToken(array $attributes)
    {
        $client = app(Client::class)->where("validation_token", $attributes['validation_token'])->first();

        if(!$client){
            return null;
        }
        $client->setIsValid(1);
        $client->setIsActive(1);
        $client->save();
        return $client;
    }
}
