<!DOCTYPE html>
<!--Description: Classify page-->
<!--Author: Liew You Wee-->
<!--Date: 15/09/2024 -->
<!--Validated: OK 13/10/2024-->
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="description" content="Classify Page">
	<meta name="keywords" content="Family, Genus, Species">
	<meta name="author" content="Liew You Wee">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Herbarium for Plant Biodiversity</title>
  <link rel="stylesheet" type="text/css" href="style/style.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body class="c1">
  
  <div class="classify-con-h">
    <?php include("navigation.php");?>
    <div class="classify-img">
      <div class="classify-content">
        <p>Plant Classification</p>
        <h1>Family, Genus, Species</h1>
        <p>A brief introduction to the classification system for plants.</p>
      </div>
    </div>
  </div>
  
  <div class="classify-b">
    <div class="classify-inner">
    <div class="classify-con-con"></div>
    <div class="classify-con">
      <div class="classify-row1">
        <img src="img/diagram.webp" alt="diagram">
      </div>
      <div class="classify-row2">
        <div>
          <h2>OverView</h2>
          <p>Plants are classified into different levels to organize and identify them based on shared characteristic. The main hierarchical levels are: </p>
          <ul>
            <li><strong>Family</strong> - A group of related plants sharing common traits.</li>
            <li><strong>Genus</strong> - A subdivision of a family, consisting of plants that are closely related.</li>
            <li><strong>Species</strong> - The most specific level of classification, identifying individual plants that can interbreed.</li>
        </ul>
        </div>
      </div>
    </div>
  </div>
    


  <div class="classify-end">
    <h2>Example</h2>
    <div class="exm-con">
      <div class="exm-row">
        <div>
          <!--https://www.inaturalist.org/taxa/68691-Dipterocarpaceae-->
          <img src="img/classify_fmy.jpg" alt="family" title="family">
        </div>
        <div>
          <h3>Dipterocarpaceae</h3>
          <p>The Dipterocarpaceae family is a significant family of tropical hardwood trees, predominantly found in Southeast Asia, including Borneo. They are ecologically important as they dominate many lowland tropical rainforests.</p>
        </div>
      </div>
  
      <div class="exm-row">
        <div>
          <!--https://www.inaturalist.org/taxa/68692-Shorea-->
          <img src="img/classify_gen.jpeg" alt="genus" title="genus">
        </div>
        <div>
          <h3>Shorea</h3>
          <p>The genus Shorea is one of the largest within the Dipterocarpaceae family. It includes about 196 species, many of which are found in Malaysia and Borneo. These trees are valued for their timber and play an important role in forest ecology.</p>
        </div>
      </div>
  
      <div class="exm-row">
        <div>
          <!--https://www.inaturalist.org/taxa/443569-Shorea-faguetiana-->
          <img src="img/classify_spi.jpeg" alt="species" title="species">
        </div>
        <div>
          <h3>Shorea Parvifolia</h3>
          <p>Shorea parvifolia is a species of tree found in the rainforests of Borneo and Peninsular Malaysia. It is known for its role in the ecosystem and is also used for timber production. It is commonly referred to as the light red meranti.</p>
        </div>
      </div>
    </div>
    
  </div>
  <?php include("footer.php");?>
</body>
</html>

