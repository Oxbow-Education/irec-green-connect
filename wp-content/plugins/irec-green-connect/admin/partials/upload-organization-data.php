<div class="wrap">
  <h1>Organization Data</h1>
  <div>
    <button class="btn btn-primary" data-csvbox disabled onclick="importer.openModal();">Import</button>
  </div>
  <script type="text/javascript" src="https://js.csvbox.io/script.js"></script>
  <script type="text/javascript">
    function callback(result, data) {
      if (result) {
        console.log("Sheet uploaded successfully");
        console.log(data.row_success + " rows uploaded");
      } else {
        console.log("There was some problem uploading the sheet");
      }
    }
    let importer = new CSVBoxImporter("oVvU25iXkwAh1oS5eASv70iRH6BJEs", {}, callback);
    importer.setUser();
  </script>
</div>