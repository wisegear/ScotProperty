<h2 class="wise1text">Gallery</h2>
<p class="text-center">Click on images to see bigger versions.</p>
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 mt-2">
    @foreach($galleryImages as $image)
		<div class="border border-slate-100 dark:border-slate-700 shadow-lg">
			<a href="{{ asset('assets/images/uploads/galleries/' . $image['original']) }}" class="venobox" data-gall="gallery">
				<img src="{{ asset('assets/images/uploads/galleries/' . $image['thumbnail']) }}" alt="Gallery Image" class="w-full h-auto rounded shadow-lg border border-gray-300 dark:border-gray-700">
			</a>
		</div>
    @endforeach
</div>

<script> 
	new VenoBox({
	    selector: 'a.venobox',
	    border: '5px',
	    numeration: true,
	    infinigall: true,
	    navigation: true,
	    spinner: 'wave'
	});
</script>