<div class="wrap"><?php screen_icon(); ?><h2>  <?php bloginfo("name");?>  Business Details</h2><form method="post" action="options.php">   <?php   settings_fields( 'mwbd-address-details' );   do_settings_sections('mwbd-address-details');   ?><div class="metabox-holder">  <h3>Default Settings</h3>  <table class="form-table postbox">    <tbody>    <?php       $mainAddress = get_option('business_address');      $addressChoice = get_option('main_address');       if ( $mainAddress ) {      ?>    <tr>      <th>Current addresses: </th>      <td>      <?php        foreach ( $mainAddress as $mainAddressChoice ) {          $addressNameSlug = str_replace( ' ', '-', $mainAddressChoice['address_name']);          $addressName = str_replace( '-', ' ', $addressNameSlug );          echo '<a class="grey-button '. strtolower($addressNameSlug) .' select-address" href="#">'.$addressName.'</a>';        }        ?>    </td>    </tr>      <?php } ?>    <tr>      <th>Tip</th>      <td>Use this section, to choose your business type and default address.</td>    </tr>    <?php     $addressCount = count( get_option('business_address') );    if ( $addressCount < 1 ) { ?>    <tr>      <th scope="row"> Add an Address! </th>            <td>Please add an address first, below.</td>    </tr>    <?php } else { ?>    <tr>      <th scope="row"> Business Type </th>            <td>      <?php      $businessType = get_option('businessType');      echo '<select name="businessType" id="businessType">'; ?>               <option value="none">-- Select --</option>          <?php          $industryType = array(            "LocalBusiness",            "Organization",            "AutomotiveBusiness",            "AutoBodyShop",            "AutoDealer",            "AutoPartsStore",            "AutoRental",            "AutoRepair",            "AutoWash",            "GasStation",            "MotorcycleDealer",            "MotorcycleRepair",            "ChildCare",            "DryCleaningOrLaundry",            "EmergencyService",            "FireStation",            "Hospital",            "PoliceStation",            "EmploymentAgency",            "EntertainmentBusiness",            "AdultEntertainment",            "AmusementPark",            "ArtGallery",            "Casino",            "ComedyClub",            "MovieTheater",            "NightClub",            "FinancialService",            "FoodEstablishment",            "Bakery",            "BarOrPub",            "Brewery",            "CafeOrCoffeeShop",            "FastFoodRestaurant",            "IceCreamShop",            "Restaurant",            "Winery",            "GovernmentOffice",            "PostOffice",            "HealthAndBeautyBusiness",            "HomeAndConstructionBusiness",            "Electrician",            "GeneralContractor",            "HVACBusiness",            "HousePainter",            "Locksmith",            "MovingCompany",            "Plumber",            "RoofingContractor",            "InternetCafe",            "Library",            "LodgingBusiness",            "BedAndBreakfast",            "Hostel",            "Hotel",            "Motel",            "MedicalOrganization",            "Dentist",            "DiagnosticLab",            "Hospital",            "MedicalClinic",            "Optician",            "Pharmacy",            "Physician",            "VeterinaryCare",            "ProfessionalService",            "AccountingService",            "Attorney",            "Dentist",            "Electrician",            "GeneralContractor",            "HousePainter",            "Locksmith",            "Notary",            "Plumber",            "RoofingContractor",            "RadioStation",            "RealEstateAgent",            "RecyclingCenter",            "SelfStorage",            "ShoppingCenter",            "SportsActivityLocation",            "BowlingAlley",            "ExerciseGym",            "GolfCourse",            "HealthClub",            "PublicSwimmingPool",            "SkiResort",            "SportsClub",            "StadiumOrArena",            "TennisComplex",            "Store",            "AutoPartsStore",            "BikeStore",            "BookStore",            "ClothingStore",            "ComputerStore",            "ConvenienceStore",            "DepartmentStore",            "ElectronicsStore",            "Florist",            "FurnitureStore",            "GardenStore",            "GroceryStore",            "HardwareStore",            "HobbyShop",            "HomeGoodsStore",            "JewelryStore",            "LiquorStore",            "MensClothingStore",            "MobilePhoneStore",            "MovieRentalStore",            "MusicStore",            "OfficeEquipmentStore",            "OutletStore",            "PawnShop",            "PetStore",            "ShoeStore",            "SportingGoodsStore",            "TireShop",            "ToyStore",            "WholesaleStore",            "TelevisionStation",            "TouristInformationCenter",            "TravelAgency",          );         foreach ( $industryType as $type ) {            echo '<option value="'.$type.'"';            if ( $businessType == $type ) {                 echo "selected";                              }             echo  '>'.$type.'</option>';           } ?>        </select>        <p class="description">Decide if you want your schema markup to be localBusiness, or organization.</p>        </td>    </tr>    <tr>      <th scope="row"> Address Choice </th>            <td>      <?php           $mainAddress = get_option('business_address');          $addressChoice = get_option('main_address');          echo '<select name="main_address" id="main_address">';          foreach ( $mainAddress as $mainAddressChoice ) {            $addressNameSlug = str_replace( ' ', '-', $mainAddressChoice['address_name']);            $addressName = str_replace( '-', ' ', $addressNameSlug );              if ( strtolower($addressNameSlug) == $addressChoice ) {                $selected = 'selected';              } else {                $selected = '';              }              echo '<option value="'.strtolower($addressNameSlug).'" '. $selected .' >'. ucwords($addressName) .'</option>';          }            echo '</select>';          ?>        <p class="description">Choose your main address. This address will prefill onto the contact page shortcode.</p>        </td>    </tr>    <?php  } ?>  <tr>        <th scope="row" > Rich Snippet Checker</th>    <td><a target="_blank" href="https://developers.google.com/structured-data/testing-tool/" class="button button-primary">Check Rich Snippets</a></td>  </tr>  </tbody></table><div class="current_business_addresses">    <?php   $currentAddresses = get_option('business_address');  if ( $currentAddresses ) {    foreach ( $currentAddresses as $currentAddressName => $currentAddressDetails ) {      //variables      $addressNameSlug = $currentAddressName;      $addressName = str_replace( '-', ' ', $currentAddressName);      $streetAddress = $currentAddressDetails['street_address'];      $addressLocality = $currentAddressDetails['address_locality'];      $addressRegion = $currentAddressDetails['address_region'];      $postCode = $currentAddressDetails['postal_code'];      $telNumber = $currentAddressDetails['telephone_number'];      $googleMapsLink = $currentAddressDetails['google_maps_link'];      ?>       <div class="address-wrapper" id="<?php echo $addressNameSlug; ?>" >      <?php echo '<h3>'. ucwords($addressName).'</h3>'; ?>      <div class="inside">            <table class="form-table postbox" >          <tbody>          <tr>              <th>Address Name</th>              <?php echo'<td><input type="text" class="regular-text address-name" name="business_address['.$currentAddressName.'][address_name]" value="'.ucwords($addressName).'" />'; ?>              <a href="#" class="button" id="deleteAddress">Delete Address</a>              <p class="description">Enter Details</p>              </td>          </tr>          <tr>              <th>Street Address</th>              <?php echo'<td><input type="text" class="regular-text" name="business_address['.$currentAddressName.'][street_address]" value="'.$streetAddress.'" />'; ?>              <p class="description">Enter Details</p>              </td>          </tr>          <tr>              <th>Address Locality</th>              <?php echo'<td><input type="text" class="regular-text" name="business_address['.$currentAddressName.'][address_locality]" value="'.$addressLocality.'" />'; ?>              <p class="description">Enter Details</p>              </td>          </tr>          <tr>              <th>Address Region</th>              <?php echo'<td><input type="text" class="regular-text" name="business_address['.$currentAddressName.'][address_region]" value="'.$addressRegion.'" />'; ?>              <p class="description">Enter Details</p>              </td>          </tr>          <tr>              <th>Post Code</th>              <?php echo '<td><input type="text" class="regular-text" name="business_address['.$currentAddressName.'][postal_code]" value="'.$postCode.'" />'; ?>              <p class="description">Enter Details</p>              </td>          </tr>          <tr>              <th>Telephone Number</th>              <?php echo '<td><input type="text" class="regular-text" name="business_address['.$currentAddressName.'][telephone_number]" value="'.$telNumber.'" />'; ?>              <p class="description">Enter Details</p>              </td>          </tr>          <tr>              <th>Google Maps Link</th>              <?php echo '<td><input type="text" class="regular-text" name="business_address['.$currentAddressName.'][google_maps_link]" value="'.$googleMapsLink.'" />'; ?>              <p class="description">Enter Details</p>              </td>          </tr>                      <?php             $address = $streetAddress. ', '.$postCode;            // var_dump($address);            $url = 'http://maps.google.com/maps/api/geocode/json?sensor=false&address='.urlencode($address);                 $data = json_decode(file_get_contents($url), true);            $dataAddress = $data['results'][0];            $geoAddress = $dataAddress['geometry']['location'];            $lat = $geoAddress['lat'];            $long = $geoAddress['lng'];            if ( $lat && $long ) { ?>            <tr class="latlong">                <th>Latitude &amp; Longitude</th>                <td>                                <?php echo '<input type="text" class="regular-text" name="business_address['.$currentAddressName.'][latitude]" value="'.$lat.'" />'; ?>                <?php echo '<input type="text" class="regular-text" name="business_address['.$currentAddressName.'][longitude]" value="'.$long.'" />'; ?>                <p class="description">This is used for the google map api</p>                </td>            </tr>          <?php } ?>          </tbody>      </table>      </div>    </div>    <?php }  // end if addresses exist  }  ?></div><h3>Add New</h3><table class="form-table postbox">  <tbody>  <tr>    <th scope="row">Address Name</th>    <td class="label"><input type="text" class="regular-text" value="" name="new_business_address" id="new_business_address" /><a href="#" class="button" id="addNewAddress">Add New</a>    <p class="description"></p></td>  </tr></tbody></table><div class="business_addresses">  </div><div class="business-save"><?php submit_button('Save Address Details'); ?></div><?php if ( $addressCount > 0 ) { ?><div class="metabox-holder shortcodeGen" id="address" ><h3>Shortcode Generator</h3>  <table class="form-table postbox">    <tbody>      <tr>        <th>Tip</th>        <td>Use this section, to generate the correct shortcode for a number, or address.</td>      </tr>      <tr>        <th scope="row"> Address or Number? </th>        <td>          <?php           echo '<select name="addressOrNumber" id="addressOrNumber">';          echo '<option value="getNumber" '. $selected .' >Number</option>';          echo '<option value="getAddress" '. $selected .' >Address</option>';          echo '</select>';          ?>          <p class="description">Do you want to get a number, or an address?.</p>        </td>       </tr>      <tr>        <th scope="row"> Address Name </th>        <td>          <?php           $mainAddress = get_option('business_address');          $addressChoice = get_option('main_address');          echo '<select name="addressSchemaChoice" id="addressSchemaChoice">';          foreach ( $mainAddress as $mainAddressChoice ) {            $addressNameSlug = str_replace( ' ', '-', $mainAddressChoice['address_name']);            $addressName = str_replace( '-', ' ', $addressNameSlug );            if ( strtolower($addressNameSlug) == $addressChoice ) {              $selected = 'selected';            } else {              $selected = '';            }            echo '<option value="'.strtolower($addressNameSlug).'" '. $selected .' >'. ucwords($addressName) .'</option>';          }            echo '</select>';          ?>          <p class="description">Choose which address you want to include in your shortcode.</p>        </td>       </tr>       <tr class="schema">        <th scope="row"> Show Schema Tags? </th>        <td>          <select name="showSchema" id="showSchema">            <option value="">-- Choose --</option>            <option value="hide">Hide Schema</option>            <option value="show">Show Schema</option>          </select>          <p class="description">Choose whether to show Schema markup, or not.</p>        </td>       </tr>       <tr>         <th scope="row">Shortcode ID</th>         <td>           <input name="shortcodeId" id="shortcodeId" type="text" class="regular-text" value="" placeholder="ID ( Eg: home, must be lowercase! )" />           <span class="required"></span>           <p class="description">Input your shortcode ID, relevant to page and location on the page.</p>         </td>             </tr>       <tr>         <th scope="row">Shortcode Title</th>         <td>           <input name="shortcodeTitle" id="shortcodeTitle" type="text" class="regular-text" value="" placeholder="Title" />           <a href="javascript:void(0)" class="button button-primary" id="generateBtn">Generate</a>           <p class="description">Leave blank for no title.</p>         </td>             </tr>         <tr>          <th scope="row">In WYSIWYG</th>          <td>           <input name="generatedShortcode" id="generatedShortcode" class="regular-text" type="text" disabled value="" ><br/><br/>           <p class="description">Use the shortcode generator to get a specific address.</p>         </td>                </tr>         <tr>          <th scope="row">In Template File (.php)</th>          <td>           <code>&lt;?php echo do_shortcode('<span id="generatedShortcodePHP"></span>'); ?&gt; </code>           <p class="description">Use the above code in your page template.</p>         </td>                </tr>    </tbody>  </table></div><?php } ?></div></form></div>