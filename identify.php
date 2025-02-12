<?php
session_set_cookie_params(0);
session_start();
include('config/main.php');

$plants = [];
$isPlant = false;
$confidence = 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['is_plant']) && $_POST['is_plant'] === 'true') {
        $isPlant = true;
        $confidence = $_POST['confidence'];

        $sql = "SELECT * FROM plant_table";
        $result = mysqli_query($conn, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $plants = mysqli_fetch_all($result, MYSQLI_ASSOC);
        }
        mysqli_free_result($result);
    } elseif (isset($_POST['confidence'])) {
        $confidence = $_POST['confidence'];
    }
}
?>

<!--Description: Identify page-->
<!--Author: Liew You Wee-->
<!--Date: 26/10/2024 -->
<!--Validated: OK 23/11/2024-->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="description" content="Identify Plant Page">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Identify Plant</title>
  <link rel="stylesheet" type="text/css" href="style/style.css">
  <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs"></script>
</head>
<body class="c1">
  <?php
    if (!isset($_SESSION['user_email'])) {
      echo '<div class="center-container">';
      echo "<h1>Please log in first.</h1>";
      echo '<a href="login.php"><button class="btn">Log In</button></a>';
      echo '</div>';   
      exit();
    } else {
      $email = $_SESSION['user_email'];
      $sql = "SELECT user_table.*, account_table.type FROM user_table JOIN account_table ON user_table.email = account_table.email WHERE user_table.email='$email'";
      $result = mysqli_query($conn, $sql);
      $account = mysqli_fetch_assoc($result);
    
      if ($account['type'] != "user") {
          echo '<div class="center-container">';
          echo "<h1>Access denied. You are not authorized to view this page.</h1>";
          echo '<a href="login.php"><button class="btn">Log In</button></a>';
          echo '</div>';
          exit();
      }
    }
  ?>

  <div class="identify-con-h">
  <?php include("navigation.php");?>
    <div class="identify-img">
      <div class="identify-content">
        <p>Identify</p>
        <h1>Identify Plant Type</h1>
        <p>Take a photo, upload it, and instantly get a name and information about your plant.</p>
      </div>
    </div>
  </div>

  <section class="pjv">
    <form id="uploadForm" enctype="multipart/form-data">
      <h2 class="upload_h2">Upload a photo</h2>
      <div class="upload_input">
        <input type="file" id="plant_photo" accept="image/*" class="file_p" required>
        <label for="plant_photo">Upload</label>
      
        <button type="button" onclick="identifyPlant()">Identify</button>
      </div>
    </form>

    <form id="resultForm" action="identify.php" method="POST" style="display: none;">
      <input name="is_plant" id="is_plant">
      <input name="confidence" id="confidence">
    </form>

    <div id="result"></div>
    <div id="confidenceDisplay"></div>

    <?php if ($isPlant && !empty($plants)){ ?>
      <div class="con-identi">
        <?php foreach ($plants as $plant){ 
          if($plant['approve'] == "approve"){?>
          <div class="plant-item">
            <h3>
              <?php echo htmlspecialchars($plant['scientific_name']); ?> (<?php echo htmlspecialchars($plant['common_name']); ?>)
              <?php 
                if (!empty($plant['family'])) {
                    echo "Family ";
                } elseif (!empty($plant['genus'])) {
                    echo "Genus " ;
                } elseif (!empty($plant['species'])) {
                    echo "Species" ;
                } else {
                    echo "No specific term available.";
                }
              ?>
            </h3>

            <img src="img/Contribute/<?php echo htmlspecialchars($plant['plants_image']); ?>" alt="Plant Image">

            <?php if (!empty($plant['description'])){ ?>
              <p><a href="download_pdf.php?id=<?php echo htmlspecialchars($plant['id']); ?>" class="btn">Download PDF</a></p>
            <?php }else{ ?>
              <p>No description available.</p>
            <?php } ?>
          </div>
        <?php }}?>
      </div>
    <?php }elseif ($_SERVER['REQUEST_METHOD'] == 'POST'){ ?>
      <p>The uploaded image is not a plant with <?php echo htmlspecialchars($confidence); ?>% confidence.</p>
    <?php } ?>
  </section>

  <script>
    let model;

    async function loadModel() {
      try {
        model = await tf.loadLayersModel('web_model/model.json');
        console.log('Model loaded successfully');
      } catch (error) {
        console.error('Error loading TensorFlow model:', error);
      }
    }
    loadModel();

    async function identifyPlant() {
      const fileInput = document.getElementById('plant_photo');
      const resultDiv = document.getElementById('result');
      const confidenceDiv = document.getElementById('confidenceDisplay');

      if (fileInput.files.length === 0) {
        resultDiv.innerHTML = "<p>Please upload an image.</p>";
        return;
      }

      const file = fileInput.files[0];
      const img = document.createElement('img');
      img.src = URL.createObjectURL(file);
      img.width = 224;
      img.height = 224;

      // Display the uploaded image
      resultDiv.innerHTML = `<p>Uploaded Image:</p>`;
      resultDiv.appendChild(img);

      img.onload = async () => {
        try {
          confidenceDiv.innerHTML = '<p>Processing image...</p>';

          const tensor = tf.browser.fromPixels(img)
            .resizeNearestNeighbor([224, 224])
            .toFloat()
            .expandDims();

          const predictions = await model.predict(tensor).data();
          console.log('Predictions:', predictions);

          const plantLabels = ["Plant", "Not a Plant"];
          const topPredictionIndex = predictions.indexOf(Math.max(...predictions));
          const identifiedLabel = plantLabels[topPredictionIndex];
          const confidence = Math.round(predictions[topPredictionIndex] * 100);

          confidenceDiv.innerHTML = `<p>This is ${identifiedLabel.toLowerCase()} with ${confidence}% confidence.</p>`;

          document.getElementById('is_plant').value = (identifiedLabel === "Plant").toString();
          document.getElementById('confidence').value = confidence;
          document.getElementById('resultForm').submit();
        } catch (error) {
          console.error('Error during prediction:', error);
          confidenceDiv.innerHTML = '<p>An error occurred while processing the image.</p>';
        }
      };
    }
  </script>
</body>
</html>
