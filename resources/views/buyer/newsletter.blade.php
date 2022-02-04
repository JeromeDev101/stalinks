<!DOCTYPE html>
<html>

<head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <style type="text/css">
        @media screen {
            @font-face {
                font-family: 'Lato';
                font-style: normal;
                font-weight: 400;
                src: local('Lato Regular'), local('Lato-Regular'), url(https://fonts.gstatic.com/s/lato/v11/qIIYRU-oROkIk8vfvxw6QvesZW2xOQ-xsNqO47m55DA.woff) format('woff');
            }

            @font-face {
                font-family: 'Lato';
                font-style: normal;
                font-weight: 700;
                src: local('Lato Bold'), local('Lato-Bold'), url(https://fonts.gstatic.com/s/lato/v11/qdgUG4U09HnJwhYI-uK18wLUuEpTyoUstqEm5AMlJo4.woff) format('woff');
            }

            @font-face {
                font-family: 'Lato';
                font-style: italic;
                font-weight: 400;
                src: local('Lato Italic'), local('Lato-Italic'), url(https://fonts.gstatic.com/s/lato/v11/RYyZNoeFgb0l7W3Vu1aSWOvvDin1pK8aKteLpeZ5c0A.woff) format('woff');
            }

            @font-face {
                font-family: 'Lato';
                font-style: italic;
                font-weight: 700;
                src: local('Lato Bold Italic'), local('Lato-BoldItalic'), url(https://fonts.gstatic.com/s/lato/v11/HkF_qI1x_noxlxhrhMQYELO3LdcAZYWl9Si6vvxL-qU.woff) format('woff');
            }
        }

        /* CLIENT-SPECIFIC STYLES */
        body,
        table,
        td,
        a {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }

        table,
        td {
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }
        td{

        }

        img {
            -ms-interpolation-mode: bicubic;
        }

        /* RESET STYLES */
        img {
            border: 0;
            height: auto;
            line-height: 100%;
            outline: none;
            text-decoration: none;
        }

        table {
            border-collapse: collapse !important;
            table-layout: fixed;
        }

        th,td {
            word-wrap: break-word;
        }

        body {
            height: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            background-color: black;
        }

        /* iOS BLUE LINKS */
        a[x-apple-data-detectors] {
            color: inherit !important;
            text-decoration: none !important;
            font-size: inherit !important;
            font-family: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
        }

        /* MOBILE STYLES */
        @media all and (max-width: 480px) {
            h1 {
                font-size: 0.7em !important;
                line-height: 32px !important;
            }

            .main-td {
                padding: 0 5% !important;
            }

            .main-text, .button-text {
                font-size: .9em !important;
            }
        }

        /* ANDROID CENTER FIX */
        div[style*="margin: 16px 0;"] {
            margin: 0 !important;
        }
    </style>
</head>

<body style="background-image: url('{{ asset('/images/background-login2.jpg') }}'); background-repeat: no-repeat;background-attachment: fixed;background-size: cover;">
<!-- HIDDEN PREHEADER TEXT -->
<div style="display: none; font-size: 1px; color: #fefefe; line-height: 1px; font-family: 'Lato', Helvetica, Arial, sans-serif; max-height: 0px; max-width: 0px; opacity: 0; overflow: hidden;"> We're thrilled to have you here! Get ready to dive into your new account. </div>
<table border="0" cellpadding="0" cellspacing="0" width="100%">

    <!-- LOGO -->
    @component('components.email_header')
        URL List Updated
    @endcomponent

    <tr>
        <td  align="center" class="main-td" style="padding: 0 15%">
            @component('components.email_body')
                @slot('main')
                    <p style="margin: 0;">Hello {{ $buyer->name }}!</p> <br />
                    <p style="margin: 0;">
                        {{ $urls['count'] }} new URL(s) are added to our list.
                    </p>

                    <div style="margin-top: 15px; margin-bottom: 15px;">
                        <ul>
                            @foreach($urls['data'] as $url)
                                <li>{{ $url }}</li>
                            @endforeach

                            @if ($urls['count'] > 5)
                                <li>
                                    <span style="font-style: italic"> ... </span>
                                </li>
                            @endif
                        </ul>
                    </div>

                    <p style="margin: 0;">
                        <a target="_blank" href="{{ url('/login') }}">
                            Login to STAlinks now
                        </a>
                        to check them out!
                    </p>
                @endslot

                @slot('button')
                @endslot

                @slot('link')
                @endslot

                @slot('closing')
                    <p style="margin: 0;">Best Regards,<br>StaLinks Team</p> <br/>
                @endslot

                <!-- email body slot -->
                @component('components.email_contacts')
                    <div style="width: 100%; text-align: center;">
                        <span style="font-size: 10px">
                            You are receiving STAlinks notification emails |

                            <a style="color: #FFA73B" target="_blank" href="{{ url('/subscribe/cancel/' . $buyer->subscription_code) }}">
                                Click here to unsubscribe
                            </a>
                        </span>
                    </div>
                @endcomponent
            @endcomponent
        </td>
    </tr>
    <tr>
        @component('components.email_footer')
        @endcomponent
    </tr>
</table>
</body>

</html>
