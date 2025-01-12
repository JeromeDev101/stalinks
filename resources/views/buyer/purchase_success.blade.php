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
        Purchase Successful
    @endcomponent

    <tr>
        <td  align="center" class="main-td" style="padding: 0 15%">
            @component('components.email_body')
                @slot('main')
                    <p style="margin: 0;">Hello {{ $user }},</p> <br /> <br />
                    <p style="margin: 0;">
                        Thank you for choosing and trusting StaLinks! Your order is now pending for seller confirmation.
                        Once confirmed, we will immediately process this accordingly with our best in-house content writer.
                    </p> <br />
                    <p style="margin: 0;">
                        The content will be on live 5 working days after you purchased the order.
                        Please do not hesitate to contact us if your have any further question.
                    </p> <br />
                    <p style="margin: 0;">
                        We would love to hear your feedback, as it helps us to serve you better! If it won't take too
                        much of your time, please share us your thoughts by answering our surveys below:
                    </p> <br/>
                @endslot

                @slot('button')
                    <tr>
                        <td bgcolor="#ffffff" align="left">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td bgcolor="#ffffff" align="center" style="padding: 20px 30px 60px 30px;">
                                        <table border="0" cellspacing="0" cellpadding="0">
                                            <tr>
                                                <td align="center" style="border-radius: 3px;">
                                                    <a 
                                                        href="{{ url('/survey/a/' . $registration->survey_code) }}"
                                                        target="_blank" class="button-text"
                                                        style="background-color: #FF9B00; font-size: 20px; font-family: Helvetica, Arial, sans-serif; color: #ffffff; text-decoration: none; padding: 15px 25px; border-radius: 2px; border: 1px solid #FFA73B; display: inline-block;">Survey A
                                                    </a>

                                                    <br/>

                                                    <a 
                                                        href="{{ url('/survey/b/' . $registration->survey_code) }}"
                                                        target="_blank" class="button-text"
                                                        style="background-color: #FF9B00; margin-top: 10px !important; font-size: 20px; font-family: Helvetica, Arial, sans-serif; color: #ffffff; text-decoration: none; padding: 15px 25px; border-radius: 2px; border: 1px solid #FFA73B; display: inline-block;">Survey B
                                                    </a>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                @endslot

                @slot('link')
                    <tr>
                        <td bgcolor="#ffffff" class="main-text" align="left" style="padding: 0px 30px 0px 30px; color: #666666; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 25px;">
                            <p style="margin: 0;">If the buttons doesn't work, please copy the links below instead and paste it in your browser:</p>
                        </td>
                    </tr> <!-- COPY -->
                    <tr>
                        <td bgcolor="#ffffff" class="main-text" align="left" style="padding: 20px 30px 20px 30px; color: #666666; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 25px;">
                            <p style="margin: 0;">
                                Survey A:
                                <a href="#" target="_blank" style="color: #FF9B00;">
                                    {{ url('/survey/a/' . $registration->survey_code) }}
                                </a>
                            </p>

                            <p style="margin: 0; margin-top: 10px !important;">
                                Survey B:
                                <a href="#" target="_blank" style="color: #FF9B00;">
                                    {{ url('/survey/b/' . $registration->survey_code) }}
                                </a>
                            </p>
                            <br/>
                        </td>
                    </tr>
                @endslot

                @slot('closing')
                    <p style="margin: 0;">Best Regards,<br>StaLinks Team</p>
                @endslot

                <!-- email body slot -->
                @component('components.email_contacts')
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
