<?php 

$addressName = $_GET['addressName'];
$addressNameSlug = str_replace( ' ', '-', $_GET['addressName']);

?>

<div class="address-wrapper new-address" id="<?php echo strtolower($addressNameSlug); ?>">

<h3><?php echo $addressName ?> </h3>

<div class="inside">

<table class="form-table postbox">

    <tbody>

    <tr>
        <th>Address Name</th>
        <?php echo'<td><input type="text" class="regular-text" name="business_address['.strtolower($addressNameSlug).'][address_name]" value="'. $addressName .'" />'; ?>
        <a href="#" class="button" id="deleteAddress">Delete Address</a>
        <p class="description">Enter Details</p>
        </td>
    </tr>

    <tr>
        <th>Street Address</th>
        <td><input type="text" class="regular-text" name="business_address[<?php echo $newBusinessAddressName; ?>][street_address]" />
        <p class="description">Enter Details</p>
        </td>
    </tr>
    <tr>
        <th>Address Locality</th>
        <td><input type="text" class="regular-text" name="business_address[<?php echo $newBusinessAddressName; ?>][address_locality]" />
        <p class="description">Enter Details</p>
        </td>
    </tr>
    <tr>
        <th>Address Region</th>
        <td><input type="text" class="regular-text" name="business_address[<?php echo $newBusinessAddressName; ?>][address_region]" />
        <p class="description">Enter Details</p>
        </td>
    </tr>
    <tr>
        <th>Post Code</th>
        <td><input type="text" class="regular-text" name="business_address[<?php echo $newBusinessAddressName; ?>][postal_code]" />
        <p class="description">Enter Details</p>
        </td>
    </tr>
    <tr>
        <th>Telephone Number</th>
        <td><input type="text" class="regular-text" name="business_address[<?php echo $newBusinessAddressName; ?>][telephone_number]" />
        <p class="description">Enter Details</p>
        </td>
    </tr>    
    <tr>
        <th>Google Maps Link</th>
        <td><input type="text" class="regular-text" name="business_address[<?php echo $newBusinessAddressName; ?>][google_maps_link]" />
        <p class="description">Enter Details</p>
        </td>
    </tr>

    </tbody>

</table>

</div>

</div>