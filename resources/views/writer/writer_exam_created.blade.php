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
        @if ($exam->attempt === 1)
            Writer's Examination Created - 1st Attempt
        @else
            Writer's Examination Created - 2nd Attempt
        @endif
    @endcomponent

    <tr>
        <td  align="center" class="main-td" style="padding: 0 15%">
            @component('components.email_body')
                @slot('main')
                    <p style="margin: 0;">Hello {{ $exam->writer->name }},</p> <br />

                    @if ($exam->attempt === 1)
                        <p style="margin: 0;">We are glad to welcome you to the team as our in-house writer!</p> <br />
                        <p style="margin: 0;">
                            Before you get started on writing an article for our clients, we want you to do a writer's exam.
                            This will help us to know and evaluate your writing skills.
                        </p> <br/>

                        <p style="margin: 0;">
                            Please note that you will be given <strong>two attempts</strong> to showcase your writing
                            skills and pass our exam. If you have failed in this first attempt, you will be given the
                            second attempt exam after <strong>three weeks</strong>.
                        </p> <br/>

                    @else
                        <p style="margin: 0;">
                            We are glad to inform you that your second attempt for the writer's examination
                            is now created.
                        </p> <br />
                    @endif

                    <p style="margin: 0;">
                        Here are the instructions for the exam:
                    </p> <br/>
                    <p>
                        1. Write an article with at least <strong>600 words</strong>. <br/>
                        2. Identify the keywords you have used in the article by using a <strong>bold</strong> character. <br/>
                        3. Use the <strong>anchor text</strong> as <strong>natural</strong>
                        as possible within your article and <strong>hyperlink</strong> it to the assigned website. <br/>
                        4. Create a creative title based on the topic(s) provided.<br/>
                        5.	Write a meta description with 110-160 characters (The spaces are counted as character).
                    </p> <br/>

                    <p style="margin: 0;">
                        <a target="_blank" href="{{ url('/login') }}">
                            Login to your account now
                        </a>
                        and go to the 'Writer's Validation' page to check it out.
                    </p> <br/>

                    <p style="margin: 0;">
                        You will be notified about the result of your exam shortly after you've finished and submitted it.
                        We wish you good luck. Thank you for your cooperation!
                    </p>
                @endslot

                @slot('button')
                @endslot

                @slot('link')
                @endslot

                @slot('closing')
                    <p style="margin: 0;">Cheers,<br>StaLinks Team</p>
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
