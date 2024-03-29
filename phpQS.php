

<html>
    <head>
    <title>
    AnCit - Analisis Citra!
    </title>
    </head>    
    <body>

    Cari citra yang ingin kamu Analisis:
    <form method="post" enctype="multipart/form-data">
        <label for="file">Nama berkas:</label>
        <input type="file" name="fileToUpload" id="fileToUpload" /> 
        <br />
        <input type="submit" name="submit" value="Cek keterangan Otomatis!" />
    </form>
    Batasan ukuran berkas: 500 kB
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

    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($_FILES["fileToUpload"]["name"],PATHINFO_EXTENSION));
    
    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    if ($_FILES["fileToUpload"]["error"] > 0 && $uploadOk !== 1) {
        echo "Error: " . $_FILES["fileToUpload"]["error"] . "<br />";
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
            $imageUrl = "https://" .  getenv('ACCOUNT_NAME') . ".blob.core.windows.net/". $containerName . "/" . $fileName;
        }
        catch ( Exception $e ) {
            error_log("Failed to upload file '".$fileName."' to storage: ". $e);
        } 
    }
   else {        
        error_log("Failed to open file '".$filePath."' to upload to storage.");
    }

    if($fileHandled==1){

        // **********************************************
        // *** Update or verify the following values. ***
        // **********************************************

        // Replace <Subscription Key> with your valid subscription key.
        $subscriptionKey = "ce94dc1aa6c342a1a65a92d9ee6277b5";

        // You must use the same Azure region in your REST API method as you used to
        // get your subscription keys. For example, if you got your subscription keys
        // from the West US region, replace "westcentralus" in the URL
        // below with "westus".
        //
        // Free trial subscription keys are generated in the "westus" region.
        // If you use a free trial subscription key, you shouldn't need to change
        // this region.
        $uriBase =
            "https://southeastasia.api.cognitive.microsoft.com/vision/v2.0/";

        require_once 'HTTP/Request2.php';

        $request = new Http_Request2($uriBase . '/analyze');
        $url = $request->getUrl();

        $headers = array(
            // Request headers
            'Content-Type' => 'application/json',
            'Ocp-Apim-Subscription-Key' => $subscriptionKey
        );
        $request->setHeader($headers);

        $parameters = array(
            // Request parameters
            'visualFeatures' => 'Description',
            'details' => '',
            'language' => 'en'
        );
        $url->setQueryVariables($parameters);

        $request->setMethod(HTTP_Request2::METHOD_POST);

        // Request body parameters
        $body = json_encode(array('url' => $imageUrl));
        // Request body
        $request->setBody($body);

        echo "<b>Tampilan citramu</b>";
        echo "<img src='".$imageUrl."'/>";
        echo "<br/>";

        try
        {
            $response = $request->send();
            $jsonObj = json_decode($response->getBody(),true);
            echo "Berikut hasil pembuatan keterangan otomatis dari citra kamu: ";
            echo $jsonObj["description"]["captions"][0]["text"];

        }
        catch (HttpException $ex)
        {
            echo "<pre>" . $ex . "</pre>";
        }
    
    }

}

?>
