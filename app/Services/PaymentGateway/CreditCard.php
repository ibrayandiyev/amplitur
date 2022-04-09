<?php

namespace App\Services\PaymentGateway;

use InvalidArgumentException;

class CreditCard
{
    public $holder;
    public $number;
    public $expirationDate;
    public $cvv;
    public $flag;

    const VISA = 'Visa';
    const MASTERCARD = 'Master';
    const AMEX = 'Amex';
    const ELO = 'Elo';
    const AURA = 'Aura';
    const JCB = 'JCB';
    const DINERS = 'Diners';
    const DISCOVER = 'Discover';
    const HIPERCARD = 'Hipercard';

    public function __construct(string $flag, string $holder, string $number, string $expirationDate, string $cvv)
    {
        $this->flag = trim($flag);
        $this->holder = trim($holder);
        $this->number = trim(str_replace(' ', '', $number));
        $this->expirationDate = trim($expirationDate);
        $this->cvv = trim($cvv);
    }

    /**
     * Check if credit card data is valid
     *
     * @return  bool
     */
    public function isValid(): bool
    {
        if (empty($this->holder) || empty($this->number) || empty($this->expirationDate) || empty($this->cvv) || empty($this->flag)) {
            throw new InvalidArgumentException;
            return false;
        }

        if (strlen($this->number) < 16) {
            throw new InvalidArgumentException;
            return false;
        }

        if (!preg_match('/^(0[1-9]|1[0-2])\/?([0-9]{4}|[0-9]{2})$/', $this->expirationDate)) {
            throw new InvalidArgumentException;
            return false;
        }

        if (!in_array($this->flag, self::flags())) {
            throw new InvalidArgumentException;
            return false;
        }

        return true;
    }

    /**
     * [flag description]
     *
     * @return  string  [return description]
     */
    static function flag(string $number): ?string
    {
        $flags = [
            self::VISA       => '/^4\d{12}(\d{3})?$/',
            self::MASTERCARD => '/^(5[1-5]\d{4}|677189)\d{10}$/',
            self::DINERS     => '/^3(0[0-5]|[68]\d)\d{11}$/',
            self::DISCOVER   => '/^6(?:011|5[0-9]{2})[0-9]{12}$/',
            self::ELO        => '/^((((636368)|(438935)|(504175)|(451416)|(636297))\d{0,10})|((5067)|(4576)|(4011))\d{0,12})$/',
            self::AMEX       => '/^3[47]\d{13}$/',
            self::JCB        => '/^(?:2131|1800|35\d{3})\d{11}$/',
            self::AURA       => '/^(5078\d{2})(\d{2})(\d{11})$/',
            self::HIPERCARD  => '/^(606282\d{10}(\d{3})?)|(3841\d{15})$/',
        ];

        $flag = null;

        foreach ($flags as $_flag => $regex) {
            if (preg_match($regex, $number)) {
                $flag = $_flag;
                break;
            }
        }

        if (is_null($flag)) {
            return self::MASTERCARD;
        }

        return $flag;
    }

    /**
     * [flags description]
     *
     * @return  array   [return description]
     */
    static function flags(): array
    {
        return [
            self::VISA,
            self::MASTERCARD,
            self::AMEX,
            self::ELO,
            self::AURA,
            self::JCB,
            self::DINERS,
            self::DISCOVER,
            self::HIPERCARD,
        ];
    }
}