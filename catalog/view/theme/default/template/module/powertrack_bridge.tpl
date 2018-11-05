<!-- EMS 
     http://opencart.me/demo_oc2/index.php?route=module/powertrack&tracking_url=http://www.ems.com.cn/ems/order/singleQuery_e&tracking_num=1234567891234
     http://opencart.me/demo_oc2/index.php?route=module/powertrack&tracking_url=http://www.citylinkexpress.com/MY/ShipmentTrack.aspx&tracking_num={tracking_code}
     http://opencart.me/demo_oc2/index.php?route=module/powertrack&tracking_url=http://www.swacargo.com/swacargo/fastTrackShipment.htm&tracking_num={tracking_code}
     http://opencart.me/demo_oc2/index.php?route=module/powertrack&tracking_url=https://www.deltacargo.com/Mobile.aspx&tracking_num={tracking_code}
     http://opencart.me/demo_oc22/upload/index.php?route=module/powertrack&tracking_url=http://www.swacargo.com/swacargo/fastTrackShipment.htm&tracking_num={tracking_code}
     https://www.customflyerprinting.com/index.php?route=module/powertrack&tracking_url=http://www.swacargo.com/swacargo/fastTrackShipment.htm&tracking_num={tracking_code}
     http://opencart.me/demo_oc22/upload/index.php?route=module/powertrack&tracking_url=https://www.deltacargo.com/Mobile.aspx&tracking_num=63420604&enctype=multipart
     https://www.customflyerprinting.com/index.php?route=module/powertrack&tracking_url=https://www.deltacargo.com/Mobile.aspx&tracking_num={tracking_code}&enctype=multipart
-->
<form action="<?php echo $tracking_url; ?>" method="post" style="display:none" <?php echo $enctype; ?> >

    
    <!-- EMS -->
    <input type="hidden" name="mailNum" value="<?php echo $tracking_num ?>" />
    
    <!-- CITYLINK -->
    <input type="hidden" name="type" value="consignment" />
    <input type="hidden" name="no"   value="<?php echo $tracking_num ?>" />
    
    <!-- SWACARGO Southwest Cargo -->
    <input type="hidden" name="airbillNumbers[0]"       value="<?php echo $tracking_num ?>" />
    <input type="hidden" name="btnAirbillNumberTrack"   value="Track" />
    <input type="hidden" name="hiddenVal"               value="1" />
    
    
    <!-- deltacargo -->
    <input type="hidden" name="StylesheetManager_TSSM" id="StylesheetManager_TSSM" value="">
    <input type="hidden" name="ScriptManager_TSM" id="ScriptManager_TSM" value=";;System.Web.Extensions, Version=4.0.0.0, Culture=neutral, PublicKeyToken=31bf3856ad364e35:en:93a6b8ed-f453-4cc5-9080-8017894b33b0:ea597d4b:b25378d2">
    <input type="hidden" name="__EVENTTARGET"  value="dnn$ctr1546$View$btnGo" />
    <input type="hidden" name="__EVENTARGUMENT" id="__EVENTARGUMENT" value="">
    <input type="hidden" name="__VIEWSTATE" id="__VIEWSTATE" value="PKdm9TKAaULQrHPgmB8EjXuKUYAo7MH+VXl8lwLn6PghNsrAVpU6dVBqQcxsZtSAWaGYPX0AHGfTKaL+lfvkfUHkGtEQSyY+RTwMXS5UFawM0a4sDhQ/uAgUnjnfiMQ8wi8VxmiWewZY/mVVJIxmrh57O14UTIxGPwhFbO8fuP2flxIqnGS6NcWN/mHNs0cUFqKd6pbFCv6hMGxrqjNlxUKnMCzsYwLsUfZpQMf8Bv8kMCWXC6Yu80/rAi+zPIaYLtYs6uqjV+5X4WsWgBxlVeU5zDm64WhlIVAaGLynaCuuueaZRlNqCYtJXPpDqFHQ+SMUiWkpzH1bgUCRO/muG58hXYkbNNOqMXbPNo0Eg/R+0h0c+rC/oXfjc/TIxrBs7EQ0MNqWEqGrzJ1ouldfNr//tYBHHOPWzs7TjzLxK0eWp+ZQKt3UCUimzv8Dw+PK+Xzlz0jDW0XzQLK02PMIIx8Yrt6NS+Gbn5cwOlwiXBNPmJomEpBhXb6yq8FsRUDLWrSYgc/uuNfGbA1DBFJV+M4Zzc6X03t/s76kpFg0DJWIzSGbqi3ANAe9WGDEN/9GUb96wSSusej+ND7diC13Gh+jtEewYeCDLEhvKXLIT6X4SMq849voMKhQqy3Ppl4424yRkcm1u9xGRl7vbM1r/z+0nSnyIpnC2MYKvC7SiXSic4cjLAgaNQpszDGDp9lyUEX4EGhdIa95DqW7yWkWDHhoJRBSVfWuU4cjazeAK/v+MB5h1JC3go9KevlRqLxO2TyQBjOBnRFPrioCyeDsAqt6f6XAr/3xkDm8CSxxP1PPoznAvWJUBZhBXMdc7EK4KPjdPgGPTzTg++aVPTx6zSR+JB5W5P1o7Ak111nSLgR2ySzA4LyZvsiZpbRpouq+/OmsT3ouw2VRMDsEhNGkg26mVrqku/qKch+xzlvuqu6KhMdu4W6ch1A5WK5XomT1B5xDVIsn0BhV7JyRCIWh+6AsYTIsfHW8c86J8KM+4tN61SYVuCEEN4I82XCYIjcJfrJni5ML/eySZ04eZTsBWsCCBOa1TF9mUDPgdI4oJnK/h21hup8l4U8rkbgHFcprydDVqEj6c1O/JabkRWoYBJSKJ7vkINPUUke3yZKX7PyEf1BW4Vj7Fz+Po065KsE6hccuALfAgSkxy7jfrUpnwTngryT7E1KewJXV4epA9rNlXcMWh1+AZOLMEpiKAW/pcDslmUfn4msIwta0WCzHeTUqiUDxBZk7OLBuNumsYpfDAg6THFdlHbPvJJ/6750ZaXmOeQGKLBdjJdHNDe/Fo0h8mAkj4Bs+Jk7ffMr47KkJHEO22oAhmpI+VtlG2Ee6UqrJs2KxsCbaNdE7RWXdpNhHxQ8efxCTr80jnqli9WPoAFKY01Au3QV+9qWyZVSxKRGjK0nl/ZZpCYO6Q0sSPXNxd2uHRNsJIotcbg==">
    <input type="hidden" name="__VIEWSTATEENCRYPTED" id="__VIEWSTATEENCRYPTED" value="">
    <input type="hidden" name="__EVENTVALIDATION" id="__EVENTVALIDATION" value="LDA07t5km445R/0ANq1O7sB7jf4JX9O6yAJ5a7edkTkxGcZ2gV8nKjDxPOgNKLdQeCHmoMyJtBhwTDvd9vIpT8vansOBrXEdEQcPiKF422aiXcaiKisxXZrJCuMOBiV5YkWDy/RCxlZeyA3lg8oGSJcUu3YcEA2/0Q+uwl655l3sziaDGEuYolN0XrATAvobnlfw87Dd/EfCHxa515gPEIWD5H8Ye2eKqVUnJFUvbvxwilMHO2DnohCMxXkPoqEGTov5PCPKbhIZ1KFdGh0+cS7lwshZjf7Qo/CrtI4inF6IbGDp3H2VKGqc+eUWmW8fSicJX16Zid/Lcopoy97HIGa1izJ6X8m8FUoJE3h/R+XYlUkp9MbFsPRJ9V7Yp+0SULyirEl6200pKzvY8jB/5vQrzCaXiYZyId7W2ySYZuXpJdb5+wgUYbBHb0YxON60d/ji+N4MTSMkVIbHDY2wr+gxgTdCR6nwEfM/qNm6NJMjEYTLBNZhE08vjATX8+p0Hn9SKiqs+ca1llAL//xciFnZzXioQ9Uj7Y8Yf/pSdc3oIaUpRBoyzprpFxi6cMAplICdQzFk5ExG3zoe">
    <input type="hidden" name="dnn$header$hdnShowLogin" value="false">
    <input type="hidden" name="dnn$header$txtUsername" value="">
    <input type="hidden" name="dnn$header$txtPassword" value="">
    <input type="hidden" name="dnn$header$hdnShowChangePassword" value="false" />
    <input type="hidden" name="dnn$header$txtNewPassword" value="" />
    <input type="hidden" name="dnn$header$txtConfirmPassword" value="" />
    <input type="hidden" name="dnn$header$hdnShowForgot" value="false" />
    <input type="hidden" name="dnn$header$txtForgotUsername" value="" />
    <input type="hidden" name="dnn$header$hdnShowAccount" value="false" />
    <input type="hidden" name="dnn$header$hdnAccountNumber" value="" />
    <input type="hidden" name="dnn$header$txtSearchString" value="Search" />
    <input type="hidden" name="dnn$footer$txtUserName" value="Username" />
    <input type="hidden" name="dnn$footer$txtPassword" value="" />
    <input type="hidden" name="ScrollTop" value="" />
    <input type="hidden" name="__dnnVariable" value="" />
    <input type="hidden" name="dnn$ctr1546$View$txtAWBNumbers" value="<?php echo $tracking_num ?>" />
    
</form>

<script>
document.forms[0].submit();
</script>