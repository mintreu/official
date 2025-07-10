<div class="homepage-section-content">
    <h1 class="text-4xl font-bold mb-6 ">Our Projects</h1>
    <p class="text-lg mb-6 ">We pride ourselves on a portfolio that demonstrates our versatility and expertise. Some of our key projects include:</p>


    @isset($records)
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($records as $record)

        <!-- Project 1 -->
        <div class="bg-black/50 p-6 rounded-lg flex flex-col shadow-black shadow-xl py-3 px-6">
            <div class="w-full h-32 bg-black rounded-lg mb-4 flex items-center justify-center ">
                <!-- Replace with project image or screenshot -->
                <img src="{{$record->getFirstMediaUrl('displayImage')}}" alt="{{$record->name}}"
                     class="object-cover rounded-lg h-40 border-4 border-fuchsia-700 shadow-black shadow-lg" />
            </div>
            <h3 class="text-xl font-semibold mt-2 mb-2">{{$record->name}}</h3>
            <p class=" ">{{ $record->short_desc }}</p>
            <a href="{{route('welcome.project.view',['project' => $record->url])}}" class="my-1 mx-auto px-6 py-1 border-2 rounded-3xl bg-black/50 flex gap-1 items-center hover:bg-blue-600 hover:border-fuchsia-600  ">@svg('heroicon-s-eye','w-8 h-8 hover:animate-pulse') View Project</a>
        </div>
        @endforeach
    </div>
    @endisset

    <p class="text-lg mt-6 ">Visit our <a href="#portfolio" class="text-blue-600 underline">portfolio page</a> to explore our work in detail.</p>
</div>
