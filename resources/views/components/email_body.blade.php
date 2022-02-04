<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td bgcolor="#ffffff" class="main-text" align="left" style="padding: 20px 30px 40px 30px; color: #666666; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 25px;">
            {{ $main }}
        </td>
    </tr>
    {{ $button }}
    {{ $link }}
    <tr>
        <td bgcolor="#ffffff" class="main-text" align="left" style="padding: 0px 30px 40px 30px; border-radius: 0px 0px 4px 4px; color: #666666; font-family: 'Lato', Helvetica, Arial, sans-serif; font-size: 18px; font-weight: 400; line-height: 25px;">
            {{ $closing }}
        </td>
    </tr>

    {{ $slot }}
</table>
