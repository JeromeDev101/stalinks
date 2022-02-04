<tr class="spacer">
    <td style="padding-top: 10px; padding-bottom: 10px">
    </td>
</tr>

<tr>
    <td align="center" class="main-td" style="padding: 0 15%">
        <table border="0" cellpadding="0" cellspacing="0" width="100%">
            <tr>
                <td bgcolor="#ffffff" class="main-text" align="left" style="padding: 20px 30px 20px 30px; color: #666666; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; border-radius: 4px">
                    <div class="sub-div" style="border: 3px solid #007E93; margin: 20px; border-radius: 10px; padding: 30px">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="">
                            <tr>
                                <td class="sub-text" width="70%">
                                    <h1 class="sub-main-header" style="margin-bottom: 0; font-size: 3vw; color: #007E93">
                                        SUBSCRIBE
                                    </h1>

                                    <h1 class="sub-sub-header" style="margin-bottom: 0; font-size: 2vw; color: #32abbf;">
                                        TO OUR
                                    </h1>

                                    <h1 class="sub-main-header" style="margin-bottom: 10px; font-size: 3vw; color: #32abbf;">
                                        NEWSLETTER
                                    </h1>

                                    <p class="main-text sub-subtitle" style="font-weight: normal; margin-bottom: 50px">
                                        Be the first to get notified for new url uploads!
                                    </p>

                                    <a href="{{ url('/subscribe/sub/' . $slot) }}"
                                       target="_blank" class="button-text"
                                       style="font-size: 20px; background-color: #FF9B00; font-family: Helvetica, Arial, sans-serif; color: #ffffff; text-decoration: none; padding: 15px 25px; border-radius: 25px; border: 1px solid #FFA73B; display: inline-block;">
                                        Subscribe
                                    </a>
                                </td>
                                <td class="sub-image" align="center" width="30%">
                                    <div>
                                        <img
                                            src="{{ asset('/images/mailsub.png') }}"
                                            style="background-repeat: no-repeat;background-attachment: fixed;background-size: cover; max-width: 500px; width: 100%; height: auto;" >
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
        </table>
    </td>
</tr>
