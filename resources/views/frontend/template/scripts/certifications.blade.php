@if (config('app.env') == 'production')
<div class="rodape__trustsign">
    <table width="100%" title="CLICK TO VERIFY: This site uses a GlobalSign SSL Certificate to secure your personal information.">
        <tr>
            <td align="center">
                <span id="s18_img_wrapper_vd_image_125-50_en">
                    <a href="https://www.globalsign.eu/" target=_blank title="SSL">
                        <img alt="SSL" border=0 id="ss_img" src="//seal.globalsign.com/SiteSeal/images/gs_noscript_125-50_en.gif">
                    </a>
                </span>
                <script src="//seal.globalsign.com/SiteSeal/valid/vd_image_125-50_en.js"></script>
                <br />
                <a href="https://www.globalsign.eu/" target=_blank style="color:#000000; text-decoration:none; display: block;text-indent:-9999px;font:bold 8px arial; margin:0px;padding:0px;">SSL Certificates from GlobalSign Certificate Authority</a>
            </td>
        </tr>
    </table>
</div>
@endif