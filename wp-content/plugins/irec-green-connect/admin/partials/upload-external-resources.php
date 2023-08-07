<div class="wrap">
  <h1>External Resources</h1>
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
    let importer = new CSVBoxImporter("fGtHUBkIvIqqzAVtdUtFqPhhW9I8GY", {}, callback);
    importer.setUser({
      user_id: "default123"
    });
  </script>
</div>