<div class="homepage-section-content">
    <!-- Introduction -->
    <div class="text-center mb-8">
        <h1 class="text-4xl font-bold mb-4">Our API Service Plans</h1>
        <p class="text-lg">
            Choose from our flexible API service plans designed to meet various needs. Whether you're starting small or require extensive features, we have a plan for you.
        </p>
    </div>

    @foreach($plans as $plan)
        @if($plan->is_recommended)
            <!-- Recommended Plan -->
            <div class="text-center mb-8 w-full">
                <div class="w-full bg-black/50 p-6 rounded-lg shadow-lg inline-block">
                    <h2 class="text-2xl font-bold mb-4 text-amber-500">Recommended</h2>
                    <h3 class="text-3xl font-bold  mb-4">{{$plan->name}}</h3>
                    @if($plan->price > 0)
                        <p class="text-xl  mb-4">{{\App\Services\MoneyService\Money::format($plan->price)}}<span class="text-base font-normal">/mo</span></p>
                    @else
                        <p class="text-4xl font-bold  mb-4">Free</p>
                    @endif
                    <p class="text-lg  mb-4">{{$plan->desc}}</p>
                    <a href="#" class="block py-2 px-4 text-center text-white bg-green-700 rounded-lg hover:bg-blue-600 transition duration-300">Subscribe</a>
                </div>
            </div>
        @endif
    @endforeach



    <!-- Pricing Plans Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">

        @foreach($plans as $plan)
            @if(!$plan->is_enterprise)

                <!-- Free Plan -->
                <div class="col-span-1 bg-black/50 p-6 rounded-lg shadow-md border border-gray-300">
                    <h3 class="text-3xl font-bold  mb-4">{{$plan->name}}</h3>

                    @if($plan->price > 0)
                        <p class="text-xl  mb-4">{{\App\Services\MoneyService\Money::format($plan->price)}}<span class="text-base font-normal">/mo</span></p>
                    @else
                        <p class="text-4xl font-bold  mb-4">Free</p>
                    @endif
                    <p class="text-center">{{$plan->desc}}</p>
                    <div class="w-full flex justify-center items-center">
                        <ul class="text-lg mb-4 space-y-2">
                            @foreach(collect($plan->features)->only('rate_limit','authentication','support','documentation','upgradable') as $key => $feature)
                                @if(is_bool($feature))
                                    <li class="text-left text-md flex flex-col justify-center items-center">{{ $feature ? 'True' : 'False' }} <br> <span class="text-sm">{{ucwords(implode(' ',explode('_',$key)))}}</span></li>

                                @else
                                    @if($key == 'rate_limit')
                                        <li class="text-left flex flex-col justify-center items-center">{{ $feature }} <br> <span class="text-sm">Request Per Month</span></li>
                                    @else
                                        <li class="text-left flex flex-col justify-center items-center">{{ $feature }} <br> <span class="text-sm">{{ucwords(implode(' ',explode('_',$key)))}}</span></li>
                                    @endif
                                @endif
                            @endforeach
                        </ul>
                    </div>
                    <a href="#" class="block w-full py-2 px-4 text-center text-white bg-fuchsia-700 rounded-lg hover:bg-blue-600 transition duration-300">Sign Up</a>
                </div>

            @endif
        @endforeach

        @foreach($plans as $plan)
           @if($plan->is_enterprise)
                <!-- Enterprise Plan -->
                <div class="bg-black/50 p-6 rounded-lg shadow-md border border-gray-300 w-full col-span-full">
                    <h3 class="text-3xl font-bold  mb-4">{{$plan->name}}</h3>

                    @if($plan->price > 0)
                        <p class="text-xl  mb-4">{{\App\Services\MoneyService\Money::format($plan->price)}}<span class="text-base font-normal">/mo</span></p>
                    @endif
                    <p class="text-center py-1 text-md">{{$plan->desc}}</p>
                    <a href="#" class="block w-full py-2 px-4 text-center text-white bg-fuchsia-700 rounded-lg hover:bg-blue-600 transition duration-300">Contact Us</a>
                </div>
            @endif
        @endforeach
    </div>





    <!-- Comparison Table -->
    <div class="pt-12 py-3 bg-black/50">
        <h2 class="text-3xl font-bold mb-4">Compare Plans</h2>
        <table class="w-full border-separate border-spacing-0 border border-gray-300 rounded-lg overflow-hidden">
            <thead class="bg-black">
            <tr>
                <th class="p-4 border-b">Feature</th>
                @foreach($plans as $plan)
                    <th class="p-4 border-b text-center">{{ $plan->name }}</th>
                @endforeach
            </tr>
            </thead>
            <tbody>
            @foreach($plans[0]->features as $key => $feature)
                <tr class="bg-black/50">
                    <!-- Feature Key -->
                    <td class="p-4 border-b">
                        @if(is_bool($feature))
                            <span class="text-sm">{{ ucwords(implode(' ', explode('_', $key))) }}</span>
                        @else
                            {{ ucwords(implode(' ', explode('_', $key))) }}
                        @endif
                    </td>

                    <!-- Feature Values for Each Plan -->
                    @foreach($plans as $plan)
                        <td class="p-4 border-b text-center">
                            @php
                                $planFeature = $plan->features[$key] ?? 'N/A';
                            @endphp

                            @if(is_bool($planFeature))
                                {{ $planFeature ? 'True' : 'False' }}
                            @else
                                @if($key == 'rate_limit')
                                    {{ $planFeature }} <br>
                                    <span class="text-sm">Requests Per Month</span>
                                @else
                                    {{ $planFeature }}
                                @endif
                            @endif
                        </td>
                    @endforeach
                </tr>
            @endforeach
            </tbody>
            <tfoot>
            <tr class="bg-black">
                <td class="p-4 text-center font-bold" colspan="{{ count($plans) + 1 }}">Ready to get started? Choose a plan that fits your needs!</td>
            </tr>
            </tfoot>
        </table>
    </div>






</div>











