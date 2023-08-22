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

            .sub-image {
                display: none;
            }

            .sub-text {
                text-align: center !important;
                width: 100%;
            }

            .sub-text .sub-main-header {
                font-size: 1.2em !important;
                text-align: center !important;
            }

            .sub-text .sub-sub-header {
                font-size: 0.9em !important;
                text-align: center !important;
            }

            .sub-div {
                margin: 0 !important;
                padding: 5% !important;
            }

            .sub-subtitle {
                margin-bottom: 30px !important;
                text-align: center !important;
            }
        }

        /* ANDROID CENTER FIX */
        div[style*="margin: 16px 0;"] {
            margin: 0 !important;
        }
    </style>
</head>

<body style="background-image: url('{{ asset('/images/background-login2.jpg') }}'); background-repeat: no-repeat;background-attachment: fixed;background-size: cover;">

<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <!-- LOGO -->
    @component('components.email_header')
    Thank You for Trusting Stalinks for Quality Backlinks!
    @endcomponent

    <tr>
        <td align="center" class="main-td" style="padding: 0 15%">
            @component('components.email_body')
                @slot('main')
                    <p style="margin: 0;">Dear {{ $user->name }},</p> <br />
                    <p style="margin: 0;">
                        You have successfully added ${{$amount}} on your Buyer account wallet. We wanted to take a moment to express our sincere
                        gratitude for choosing Stalinks as your trusted platform for quality backlinks. Your trust in our system means a lot to us.
                    </p> <br />
                    <p style="margin: 0;">
                        We're thrilled to see that you're now able to take advantage of our advanced features to select the best quality
                        backlinks for your needs. Our team is committed to continuously enhancing our services to provide you with the most
                        effective solutions for your online success. Your feedback and insights are invaluable to us, as they guide us
                        towards making Stalinks even more user-friendly and effective. If it won't take much of your time please share us
                        your thought by filling our survey below:
                    </p> <br/>
                @endslot

                @slot('button')
                    @if ($user->registration->survey_code)
                        <tr>
                            <td bgcolor="#ffffff" align="left">
                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td bgcolor="#ffffff" align="center" style="padding: 20px 30px 60px 30px;">
                                            <table border="0" cellspacing="0" cellpadding="0">
                                                <tr>
                                                    <td align="center" style="border-radius: 3px;">
                                                        <a
                                                            href="{{ url('/survey/a/' . $user->registration->survey_code) }}"
                                                            target="_blank" class="button-text"
                                                            style="background-color: #FF9B00; font-size: 20px; font-family: Helvetica, Arial, sans-serif; color: #ffffff; text-decoration: none; padding: 15px 25px; border-radius: 2px; border: 1px solid #FFA73B; display: inline-block;">Survey A
                                                        </a>

                                                        {{-- <br/>

                                                        <a
                                                            href="{{ url('/survey/b/' . $user->registration->survey_code) }}"
                                                            target="_blank" class="button-text"
                                                            style="background-color: #FF9B00; margin-top: 10px !important; font-size: 20px; font-family: Helvetica, Arial, sans-serif; color: #ffffff; text-decoration: none; padding: 15px 25px; border-radius: 2px; border: 1px solid #FFA73B; display: inline-block;">Survey B
                                                        </a> --}}
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    @endif
                @endslot

                @slot('link')
                    @if ($user->registration->survey_code)
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
                                        {{ url('/survey/a/' . $user->registration->survey_code) }}
                                    </a>
                                </p>

                                {{-- <p style="margin: 0; margin-top: 10px !important;">
                                    Survey B:
                                    <a href="#" target="_blank" style="color: #FF9B00;">
                                        {{ url('/survey/b/' . $user->registration->survey_code) }}
                                    </a>
                                </p>
                                <br/> --}}
                            </td>
                        </tr>
                    @endif
                @endslot

                @slot('closing')
                    <p style="margin: 0;">
                        If you have any questions or need assistance, feel free to reach out. Thank you once again for being a valued member of the Stalinks community.
                    </p> <br> <br>

                    <p style="margin: 0;">Best regards,<br>StaLinks Team</p>
                @endslot

                <!-- email body slot -->
                @component('components.email_contacts')
                @endcomponent
            @endcomponent
        </td>
    </tr>

    @if ($user->subscription_code && $user->is_subscribed == 0)
        @component('components.email_subscription')
            {{ $user->subscription_code }}
        @endcomponent
    @endif

    <tr>
        @component('components.email_footer')
        @endcomponent
    </tr>
</table>
</body>

</html>
