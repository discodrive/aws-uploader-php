<?php 
// Setup Options page for AWS

// If user doesn't have permissions to edit options deny them access
if (! current_user_can('manage_options'))
{
    exit();
}

// Array of options fields
$settings = ['apuBucketName', 'apuBucketRegion', 'apuAccessKeyId', 'apuSecretAccessKey'];

// Loop through the array
for ($i = 0; $i < count($settings); $i++) { 
    // If an option is set, assign it to a variable
    if (get_option($settings[$i])) {
        $$settings[$i] = get_option($settings[$i]);
    }

    if (isset($_POST[$settings[$i]]) && wp_verify_nonce( $_POST['_wpnonce'])) {
        update_option($settings[$i], $_POST[$settings[$i]]);
    }
}
?>

<div class="wrap">
    <h2>AWS Browser Upload Options</h2>
    
    <form action="" method="post">
        <?php wp_nonce_field(); ?>
        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row"><label for="apuBucketName">Bucket Name</label></th>
                    <td>
                        <input type="text" name="apuBucketName" value="<?php echo $apuBucketName; ?>" id="apuBucketName" class="regular-text"/>
                        <p class="description">The name of your bucket exactly as it appears on AWS S3</p>
                        <span class="error"><?php echo bucketError(); ?></span>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="apuBucketRegion">Bucket Region</label></th>
                    <td>
                        <input type="text" name="apuBucketRegion" value="<?php echo $apuBucketRegion; ?>" id="apuBucketRegion" class="regular-text"/>
                        <p class="description">The region of your bucket e.g. "eu-west-1"</p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="apuAccessKeyId">Access Key ID</label></th>
                    <td>
                        <input type="text" name="apuAccessKeyId" value="<?php echo $apuAccessKeyId; ?>" id="apuAccessKeyId" class="regular-text"/>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="apuSecretAccessKey">Access Key Secret</label></th>
                    <td>
                        <input type="text" name="apuSecretAccessKey" value="<?php echo $apuSecretAccessKey; ?>" id="apuSecretAccessKey" class="regular-text"/>
                    </td>
                </tr>
                <tr>
                    <th scope="row"></th>
                    <td>
                        <p class="submit"><input type="submit" value="Save Settings" class="button button-primary"></p>
                    </td>
                </tr>
            </tbody>
        </table>
    </form>
    <?php getBucketContents(); ?>
</div>
