<?php

require_once 'vendor/autoload.php';
require_once "./random_string.php";

use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
use MicrosoftAzure\Storage\Blob\Models\ListBlobsOptions;
use MicrosoftAzure\Storage\Blob\Models\CreateContainerOptions;
use MicrosoftAzure\Storage\Blob\Models\PublicAccessType;

$connectionString = "DefaultEndpointsProtocol=https;AccountName=centaurystorage;AccountKey=YDRFUmY4V7OiLM14Xq3c+aDgUSyt0WNRUCYfiqru8axogJ+IjHnVkU+YBzw7cTb0HfqPpP3dkwnrzVf2vLBbBQ=";

$blobClient = BlobRestProxy::createBlobService($connectionString);

$fileToUpload = "img/city.png";

$createContainerOptions = new CreateContainerOptions();

$createContainerOptions->setPublicAccess(PublicAccessType::CONTAINER_AND_BLOBS);

$createContainerOptions->addMetaData("key1", "value1");
$createContainerOptions->addMetaData("key2", "value2");

$containerName = "blockblobs".generateRandomString();

try {
    $blobClient->createContainer($containerName, $createContainerOptions);

    $myFile = fopen($fileToUpload, "r") or die("unable to open file!");
    fclose($myFile);

    echo "Uploading BlockBlob: ".PHP_EOL;
    echo $fileToUpload;
    echo "<br />";

    $content = fopen($fileToUpload, "r");

    $blobClient->createBlockBlob($containerName, $fileToUpload, $content);

    $listBlobsOptions = new ListBlobsOptions();
    $listBlobsOptions->setPrefix("City");

    echo "These are the blobs present in the container: ";

    do{
        $result = $blobClient->listBlobs($containerName, $listBlobsOptions);
        foreach ($result->getBlobs() as $blob)
        {
            echo $blob->getName().": ".$blob->getUrl()."<br />";
        }
    
        $listBlobsOptions->setContinuationToken($result->getContinuationToken());
    } while($result->getContinuationToken());
    echo "<br />";

    echo "This is the content of the blob uploaded: ";
    $blob = $blobClient->getBlob($containerName, $fileToUpload);
    fpassthru($blob->getContentStream());
    echo "<br />";
}
catch(ServiceException $e){
    $code = $e->getCode();
    $error_message = $e->getMessage();
    echo $code.": ".$error_message."<br />";
}
catch(InvalidArgumentTypeException $e){
    $code = $e->getCode();
    $error_message = $e->getMessage();
    echo $code.": ".$error_message."<br />";
}
?>