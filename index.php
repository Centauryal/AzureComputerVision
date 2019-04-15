<!DOCTYPE html>
<html>
  <head>
    <title>Analyze Sample</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
  </head>
  <body>
    <script type="text/javascript">
      function processImage() {
        var subscriptionKey = "90c0b5bd1df84e3fafdd92fe2a64af58";

        var uriBase =
          "https://southeastasia.api.cognitive.microsoft.com/vision/v2.0/analyze";

        // Request parameters.
        var params = {
          visualFeatures: "Categories,Description,Color",
          details: "",
          language: "en"
        };

        // Display the image.
        var sourceImageUrl = document.getElementById("inputImage").value;
        document.querySelector("#sourceImage").src = sourceImageUrl;

        // Make the REST API call.
        $.ajax({
          url: uriBase + "?" + $.param(params),

          // Request headers.
          beforeSend: function(xhrObj) {
            xhrObj.setRequestHeader("Content-Type", "application/json");
            xhrObj.setRequestHeader(
              "Ocp-Apim-Subscription-Key",
              subscriptionKey
            );
          },

          type: "POST",

          // Request body.
          data: '{"url": ' + '"' + sourceImageUrl + '"}'
        })

          .done(function(data) {
            // Show formatted JSON on webpage.
            $("#responseTextArea").val(JSON.stringify(data, null, 2));
            $("#desc").text(data.description.captions[0].text);
          })

          .fail(function(jqXHR, textStatus, errorThrown) {
            // Display error message.
            var errorString =
              errorThrown === ""
                ? "Error. "
                : errorThrown + " (" + jqXHR.status + "): ";
            errorString +=
              jqXHR.responseText === ""
                ? ""
                : jQuery.parseJSON(jqXHR.responseText).message;
            alert(errorString);
          });
      }
    </script>

    <h1>Analyze image:</h1>
    Enter the URL to an image, then click the
    <strong>Analyze image</strong> button.
    <br />
    <br />
    Image to analyze:
    <input
      type="text"
      name="inputImage"
      id="inputImage"
      value="https://centaurystorage.blob.core.windows.net/blockblobsaaaaaa/img/city.png"
    />
    <button onclick="processImage()">Analyze image</button>
    <br /><br />
    <div id="wrapper" style="width:1020px; display:table;">
      <div id="jsonOutput" style="width:600px; display:table-cell;">
        Response:
        <br /><br />
        <textarea
          id="responseTextArea"
          class="UIInput"
          style="width:580px; height:400px;"
        ></textarea>
      </div>
      <div id="imageDiv" style="width:420px; display:table-cell;">
        Source image:
        <br /><br />
        <img id="sourceImage" width="400" />
        <br /><br />
        <p id="desc"></p>
      </div>
    </div>
  </body>
</html>
