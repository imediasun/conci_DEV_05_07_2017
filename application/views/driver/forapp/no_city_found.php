<?php
$this->load->view('site/templates/common_header');
?>
<link rel="stylesheet" href="css/web_view.css">
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&amp;sensor=false"></script>

<script type="text/javascript">
    function initialize() {
        autocomplete = new google.maps.places.Autocomplete((document.getElementById('autocomplete')), {types: ['geocode']});


        google.maps.event.addListener(autocomplete, 'place_changed', function () {
			var place = autocomplete.getPlace();
			$('#latVal').val(place.geometry.location.lat());
			$('#lonVal').val(place.geometry.location.lng());
            fillInAddress();
        });


        google.maps.event.addListener(autocomplete, 'place_changed', function () {
            var place = autocomplete.getPlace();
            var address = '';
            for (var i = 0; i < place.address_components.length; i++) {
                var addressType = place.address_components[i].types[0];
                if (componentForm[addressType]) {
                    var val = place.address_components[i][componentForm[addressType]];
                    address += val + ',';

                }
            }

            var loc_street = jQuery('#route').val();
            var loc_city = jQuery('#locality').val();
            var loc_state = jQuery('#administrative_area_level_1').val();
            var loc_country = jQuery('#country').val();
            var loc_postal_code = jQuery('#postal_code').val();
            var loc_postal_code = jQuery('#postal_code').val();
        });
    }

    var speedTest = {};
    speedTest.map = null;
    speedTest.markerClusterer = null;
    speedTest.markers = [];
    speedTest.infoWindow = null;


    var placeSearch, autocomplete;
    var componentForm = {
        street_number: 'short_name',
        route: 'long_name',
        locality: 'long_name',
        administrative_area_level_1: 'short_name',
        country: 'long_name',
        postal_code: 'short_name'
    };




    function fillInAddress() {
        // Get the place details from the autocomplete object.
        var place = autocomplete.getPlace();

        for (var component in componentForm) {
            document.getElementById(component).value = '';
            document.getElementById(component).disabled = false;
        }

        // Get each component of the address from the place details
        // and fill the corresponding field on the form.
        for (var i = 0; i < place.address_components.length; i++) {
            var addressType = place.address_components[i].types[0];
            if (componentForm[addressType]) {
                var val = place.address_components[i][componentForm[addressType]];
                document.getElementById(addressType).value = val;
            }
        }
        var cityName = jQuery('#locality').val();
		 var dr_lat = jQuery('#latVal').val();
        var dr_lon = jQuery('#lonVal').val();
        /* var address = jQuery('#route').val(); 
         var state = jQuery('#administrative_area_level_1').val(); 
         var country = jQuery('#country').val(); 
         var postal_code = jQuery('#postal_code').val();  */

        var purl = 'driver/profile/ajax_check_location';
        jQuery.post(purl, {'dr_cityName': cityName,'dr_lat': dr_lat,'dr_lon': dr_lon}, function (json) {
            jQuery('#locate_btn').attr('href', 'app/driver/signup/' + json.loc);
            window.location.href = 'app/driver/signup/' + json.loc;
        }, "json")

    }

</script>
<script type="text/javascript">
    google.maps.event.addDomListener(window, 'load', speedTest.init);
    google.maps.event.addDomListener(window, 'load', initialize);
</script>


<link rel="stylesheet" href="css/site/screen.css">


</head>
<body class="">
    <div class="dark bg_img">
        <div class="sign_up_base col-lg-12">
            <div class="container-new">
               
                <div class="col-md-5 sign_up_center">
                    
                    <div class="sign_up_start text-center">
                        <h1>	<?php
                              
                            if($this->uri->segment(3,0)=='Nocategory'){
                               if ($this->lang->line('dash_no_available_category') != '')
                                  echo  $dash_no_available_category = stripslashes($this->lang->line('dash_no_available_category'));
                              else
                                echo $dash_no_available_category = 'No Category Available In Your Location';
                            }
                            else
                            {
                            if ($this->lang->line('driver_we_not_in') != '')
                                echo stripslashes($this->lang->line('driver_we_not_in'));
                            else
                                echo 'We\'re not in your city yet';
                            ?></h1>
                        <p>	<?php
                            if ($this->lang->line('driver_dont_live') != '')
                                echo stripslashes($this->lang->line('driver_dont_live'));
                            else
                                echo 'Don\'t live here? Find your city';
                            }?></p>
                        <div class="sign_up_btn col-lg-10">
                            <input type="button" class=" btn1 sign_up_btn1" value="	<?php
                            if ($this->lang->line('driver_sign_up_to_drive') != '')
                                echo stripslashes($this->lang->line('driver_sign_up_to_drive'));
                            else
                                echo 'SIGN UP TO DRIVE';
                            ?>">
                        </div>

                        <div class="sign_up_search col-lg-10 nopadd">
                            <input type="text" class="form-control sign_up_search_text" placeholder="	<?php
                            if ($this->lang->line('driver_city_town') != '')
                                echo stripslashes($this->lang->line('driver_city_town'));
                            else
                                echo 'Enter your city or town';
                            ?>" id="autocomplete">
                            <a href="javascript:void(0);" id="locate_btn"></a>
								<input id="latVal" type="hidden" value="0" />
								<input id="lonVal" type="hidden"  value="0" />
                        </div>




                        <table id="address" style="display:none;">
                            <tr>
                                <td class="label">	<?php
                                    if ($this->lang->line('driver_street_addr') != '')
                                        echo stripslashes($this->lang->line('driver_street_addr'));
                                    else
                                        echo 'Street address';
                                    ?></td>
                                <td class="slimField">
                                    <input class="field" id="street_number" disabled="true"></input></td>
                                <td class="wideField" colspan="2">
                                    <input class="field" id="route" disabled="true"></input></td>
                            </tr>
                            <tr>
                                <td class="label">	<?php
                                    if ($this->lang->line('driver_city') != '')
                                        echo stripslashes($this->lang->line('driver_city'));
                                    else
                                        echo 'City';
                                    ?></td>
                                <td class="wideField" colspan="3">
                                    <input class="field" id="locality" disabled="true"></input></td>
                            </tr>
                            <tr>
                                <td class="label">	<?php
                                    if ($this->lang->line('driver_state') != '')
                                        echo stripslashes($this->lang->line('driver_state'));
                                    else
                                        echo 'State';
                                    ?></td>
                                <td class="slimField">
                                    <input class="field" id="administrative_area_level_1" disabled="true"></input></td>
                                <td class="label">	<?php
                                    if ($this->lang->line('cms_zip') != '')
                                        echo stripslashes($this->lang->line('cms_zip'));
                                    else
                                        echo 'Zip code';
                                    ?></td>
                                <td class="wideField">
                                    <input class="field" id="postal_code" disabled="true"></input></td>
                            </tr>
                            <tr>
                                <td class="label">	<?php
                                    if ($this->lang->line('driver_country') != '')
                                        echo stripslashes($this->lang->line('driver_country'));
                                    else
                                        echo 'Country';
                                    ?></td>
                                <td class="wideField" colspan="3">
                                    <input class="field" id="country" disabled="true"></input></td>
                            </tr>
                        </table>


                    </div>
                </div>
            </div>
        </div>
        <script>
            jQuery('.sign_up_search').hide();
            jQuery(document).ready(function () {
                jQuery('.sign_up_btn1').click(function () {
                    jQuery('.sign_up_search').show();
                    jQuery('.sign_up_btn1').hide();

                })

            });
        </script>
</body>
</html>