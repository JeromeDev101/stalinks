<tr>
    <td bgcolor="#007E93" class="main-text" align="left" style="margin-top: 10px; padding: 10px 30px 15px 10px; border-radius: 0px 0px 4px 4px; color: #ffffff; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 25px;">
        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="">
            <tr>
                <td align="center" valign="top" style="color: #ffffff !important; text-align: center !important; font-family: 'Lato', Helvetica, Arial, sans-serif;">
                    <p style="margin-top: 0; font-size: 14px; font-weight: bold; color: #ffffff !important; text-align: center !important;">Let's stay connected</p>
                </td>
            </tr>

            <tr>
                <td align="center" valign="top" style="color: #ffffff; font-family: 'Lato', Helvetica, Arial, sans-serif;">
                    <ul style="padding: 0; margin: 0; text-align: center !important;">
                        <li style="display: inline-block; margin: 0 10px 0 10px">
                            <a target="_blank" href="https://www.facebook.com/StaLinksGuestPost">
                                <img src="{{ asset('/images/facebook.png') }}" style="background-repeat: no-repeat;background-attachment: fixed;background-size: cover; width: 100%; height: auto;">
                            </a>
                        </li>
                        <li style="display: inline-block; margin: 0 10px 0 10px">
                            <a target="_blank" href="https://www.linkedin.com/company/stalinks">
                                <img src="{{ asset('/images/linkedin.png') }}" style="background-repeat: no-repeat;background-attachment: fixed;background-size: cover; width: 100%; height: auto;">
                            </a>
                        </li>
                        <li style="display: inline-block; margin: 0 10px 0 10px">
                            <a target="_blank" href="https://twitter.com/stalink_jp?s=21">
                                <img src="{{ asset('/images/twitter.png') }}" style="background-repeat: no-repeat;background-attachment: fixed;background-size: cover; width: 100%; height: auto;">
                            </a>
                        </li>
                    </ul>
                </td>
            </tr>

            <tr>
                <td align="center" valign="top" style="color: #ffffff; font-family: 'Lato', Helvetica, Arial, sans-serif;">
                    <p style="margin-top: 10px; margin-bottom: 0; font-size: 10px; font-weight: bold; text-transform: uppercase; color: #ffffff !important; text-align: center !important;">
                        Stalinks | Unit 912, 9/F, Two Harbourfront, 22 Tak Fung Street,Hunghom, Kowloon, Hong Kong
                    </p>
                </td>
            </tr>

            <tr>
                <td align="center" valign="top" style="color: #ffffff; font-family: 'Lato', Helvetica, Arial, sans-serif;">
                    {{ $slot }}
                </td>
            </tr>
        </table>
    </td>
</tr>
