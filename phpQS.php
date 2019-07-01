

<html>
    <head>
    <title>
    Yuk analisis citra yang kamu punya!
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
    </title>
    </head>    
    <body>
        <form method="post" enctype="multipart/form-data">
            <label for="file">Filename:</label>
            <input type="file" name="fileToUpload" id="fileToUpload" /> 
            <br />
            <input type="submit" name="submit" value="Submit" />
        </form>

        <!--<form method="post" action="phpQS.php?Cleanup&containerName=<?php echo $containerName; ?>">
            <button type="submit">Press to clean up all resources created by this sample</button>
        </form>-->
    </body>
        
</html>


<?php
/**----------------------------------------------------------------------------------
* Microsoft Developer & Platform Evangelism
*
* Copyright (c) Microsoft Corporation. All rights reserved.
*
* THIS CODE AND INFORMATION ARE PROVIDED "AS IS" WITHOUT WARRANTY OF ANY KIND, 
* EITHER EXPRESSED OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE IMPLIED WARRANTIES 
* OF MERCHANTABILITY AND/OR FITNESS FOR A PARTICULAR PURPOSE.
*----------------------------------------------------------------------------------
* The example companies, organizations, products, domain names,
* e-mail addresses, logos, people, places, and events depicted
* herein are fictitious.  No association with any real company,
* organization, product, domain name, email address, logo, person,
* places, or events is intended or should be inferred.
*----------------------------------------------------------------------------------
**/

/** -------------------------------------------------------------
# Azure Storage Blob Sample - Demonstrate how to use the Blob Storage service. 
# Blob storage stores unstructured data such as text, binary data, documents or media files. 
# Blobs can be accessed from anywhere in the world via HTTP or HTTPS. 
#
# Documentation References: 
#  - Associated Article - https://docs.microsoft.com/en-us/azure/storage/blobs/storage-quickstart-blobs-php 
#  - What is a Storage Account - http://azure.microsoft.com/en-us/documentation/articles/storage-whatis-account/ 
#  - Getting Started with Blobs - https://azure.microsoft.com/en-us/documentation/articles/storage-php-how-to-use-blobs/
#  - Blob Service Concepts - http://msdn.microsoft.com/en-us/library/dd179376.aspx 
#  - Blob Service REST API - http://msdn.microsoft.com/en-us/library/dd135733.aspx 
#  - Blob Service PHP API - https://github.com/Azure/azure-storage-php
#  - Storage Emulator - http://azure.microsoft.com/en-us/documentation/articles/storage-use-emulator/ 
#
**/


require_once 'vendor/autoload.php';

use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
use MicrosoftAzure\Storage\Blob\Models\ListBlobsOptions;
use MicrosoftAzure\Storage\Blob\Models\CreateContainerOptions;
use MicrosoftAzure\Storage\Blob\Models\PublicAccessType;



    



$connectionString = "DefaultEndpointsProtocol=https;AccountName=".getenv('ACCOUNT_NAME').";AccountKey=".getenv('ACCOUNT_KEY');

// Create blob client.
$blobClient = BlobRestProxy::createBlobService($connectionString);
$containerName = "blobimagecognitives";

//$fileToUpload = "HelloWorld.txt";

if(isset($_POST['submit'])) {
/*
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($_FILES["fileToUpload"]["name"],PATHINFO_EXTENSION));
    
    if ($_FILES["fileToUpload"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }
    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    if ($_FILES["fileToUpload"]["error"] > 0 && $uploadOk !== 1) {
        echo "Error: " . $_FILES["fileToUpload"]["error"] . "<br />";
    } else {
        echo "Upload: " . $_FILES["fileToUpload"]["name"] . "<br />";
        echo "Type: " . $_FILES["fileToUpload"]["type"] . "<br />";
        echo "Size: " . ($_FILES["fileToUpload"]["size"] / 1024) . " Kb<br />";
        echo "Stored in: " . $_FILES["fileToUpload"]["tmp_name"];

    }

    $filePath = $_FILES["fileToUpload"]["tmp_name"];
    $fileName = $_FILES["fileToUpload"]["name"];
    $handle = @fopen($filePath, "r");

    $fileHandled = 0;
    if($handle){
        try{
            $blobClient->createBlockBlob($containerName, $fileName, $handle, $options);
            @fclose($handle);

            $fileHandled = 1;
        }
        catch ( Exception $e ) {
            error_log("Failed to upload file '".$file."' to storage: ". $e);
        } 
    }
   else {        
        error_log("Failed to open file '".$filePath."' to upload to storage.");
    }

*/
$fileHandled=1;

    if($fileHandled==1){

                    // **********************************************
        // *** Update or verify the following values. ***
        // **********************************************

        // Replace <Subscription Key> with your valid subscription key.
        $subscriptionKey = "f865f8b339da408499ef51fcd9249ba6";

        // You must use the same Azure region in your REST API method as you used to
        // get your subscription keys. For example, if you got your subscription keys
        // from the West US region, replace "westcentralus" in the URL
        // below with "westus".
        //
        // Free trial subscription keys are generated in the "westus" region.
        // If you use a free trial subscription key, you shouldn't need to change
        // this region.
        $uriBase =
            "https://southeastasia.api.cognitive.microsoft.com/vision/v2.0/analyze";
        $imageUrl = 'https://upload.wikimedia.org/wikipedia/commons/3/3c/Shaki_waterfall.jpg';

        require_once 'HTTP/Request2.php';

        echo "HELL1!";

        $request = new Http_Request2($uriBase . '/analyze');
        $url = $request->getUrl();

        echo "HELL2!";

        $headers = array(
            // Request headers
            'Content-Type' => 'application/json',
            'Ocp-Apim-Subscription-Key' => $subscriptionKey
        );
        $request->setHeader($headers);

        $parameters = array(
            // Request parameters
            'visualFeatures' => 'Categories,Description',
            'details' => '',
            'language' => 'en'
        );
        $url->setQueryVariables($parameters);


        echo "HELL3!";
        echo $url;

        $request->setMethod(HTTP_Request2::METHOD_POST);

        // Request body parameters
        $body = json_encode(array('url' => $imageUrl));

        // Request body
        $request->setBody($body);
        echo $request;

        try
        {
            $response = $request->send();
            echo "HELL5!";
            echo "<pre>" .
                json_encode(json_decode($response->getBody()), JSON_PRETTY_PRINT) . "</pre>";
        }
        catch (HttpException $ex)
        {
            echo "HELL6!";
            echo "<pre>" . $ex . "</pre>";
        }
    }

}

function ProcessImage(){


}


/*
if(!isset($_GET["Analyze"])){
        do{
            $result = $blobClient->listBlobs($containerName, $listBlobsOptions);
            foreach ($result->getBlobs() as $blob)
            {
                echo $blob->getName().": ".$blob->getUrl()."<br />";
            }
        
            $listBlobsOptions->setContinuationToken($result->getContinuationToken());
        } while($result->getContinuationToken());
}


else if (!isset($_GET["Cleanup"])) {
    // Create container options object.
    $createContainerOptions = new CreateContainerOptions();

    // Set public access policy. Possible values are
    // PublicAccessType::CONTAINER_AND_BLOBS and PublicAccessType::BLOBS_ONLY.
    // CONTAINER_AND_BLOBS:
    // Specifies full public read access for container and blob data.
    // proxys can enumerate blobs within the container via anonymous
    // request, but cannot enumerate containers within the storage account.
    //
    // BLOBS_ONLY:
    // Specifies public read access for blobs. Blob data within this
    // container can be read via anonymous request, but container data is not
    // available. proxys cannot enumerate blobs within the container via
    // anonymous request.
    // If this value is not specified in the request, container data is
    // private to the account owner.
    $createContainerOptions->setPublicAccess(PublicAccessType::CONTAINER_AND_BLOBS);

    // Set container metadata.
    $createContainerOptions->addMetaData("key1", "value1");
    $createContainerOptions->addMetaData("key2", "value2");

    try {
        // Create container.
        //$blobClient->createContainer($containerName, $createContainerOptions);

        // Getting local file so that we can upload it to Azure



        //echo file_get_contents($fileToUpload);
        
        

        echo "These are the blobs present in the container: ";


        echo "<br />";

        // Get blob.
        echo "This is the content of the blob uploaded: ";
        $blob = $blobClient->getBlob($containerName, $fileToUpload);
        fpassthru($blob->getContentStream());
        echo "<br />";
    }
    catch(ServiceException $e){
        // Handle exception based on error codes and messages.
        // Error codes and messages are here:
        // http://msdn.microsoft.com/library/azure/dd179439.aspx
        $code = $e->getCode();
        $error_message = $e->getMessage();
        echo $code.": ".$error_message."<br />";
    }
    catch(InvalidArgumentTypeException $e){
        // Handle exception based on error codes and messages.
        // Error codes and messages are here:
        // http://msdn.microsoft.com/library/azure/dd179439.aspx
        $code = $e->getCode();
        $error_message = $e->getMessage();
        echo $code.": ".$error_message."<br />";
    }
} 
else 
{

    try{
        // Delete container.
        echo "Deleting Container".PHP_EOL;
        echo $_GET["containerName"].PHP_EOL;
        echo "<br />";
        $blobClient->deleteContainer($_GET["containerName"]);
    }
    catch(ServiceException $e){
        // Handle exception based on error codes and messages.
        // Error codes and messages are here:
        // http://msdn.microsoft.com/library/azure/dd179439.aspx
        $code = $e->getCode();
        $error_message = $e->getMessage();
        echo $code.": ".$error_message."<br />";
    }
}*/
?>
