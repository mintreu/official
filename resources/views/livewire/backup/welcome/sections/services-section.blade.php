<div class="homepage-section-content">
    <h1 class="text-4xl font-bold mb-6">Our Services</h1>
    <p class="text-lg mb-6">Mintreu offers a comprehensive suite of services designed to support your digital transformation journey. Explore our key offerings:</p>

    @isset($records)
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-8">

        @foreach($records as $record)

        <!-- Service 1 -->
        <div class="bg-black/50  grow border-2 border-fuchsia-700 p-6 rounded-lg shadow-md flex items-start ">

            <div class="flex flex-col gap-2 items-center  shadow-black shadow-lg py-3 px-6">
{{--                @svg('heroicon-m-rectangle-group','w-10 h-10')--}}
                @svg('heroicon-m-beaker','w-10 h-10')
                <h3 class="text-xl font-semibold  mb-2">{{$record->name}}</h3>
                <p class="">{{$record->short_desc}}</p>
            </div>

        </div>
        @endforeach



    </div>
    @endisset
</div>
