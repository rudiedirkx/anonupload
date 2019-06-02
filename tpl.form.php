
<style>
.file, p {
	margin: 1em 0;
}
</style>

<form action method="post" enctype="multipart/form-data">
	<div class="file"><input type="file" name="files[]" multiple /></div>
	<div class="file"><input type="file" name="files[]" multiple /></div>
	<div class="file"><input type="file" name="files[]" multiple /></div>
	<div class="file"><input type="file" name="files[]" multiple /></div>
	<div class="file"><input type="file" name="files[]" multiple /></div>
	<div class="file"><input type="file" name="files[]" multiple /></div>

	<p>Password: <input name="password" type="password" /></p>

	<p><button>Upload</button></p>
</form>
