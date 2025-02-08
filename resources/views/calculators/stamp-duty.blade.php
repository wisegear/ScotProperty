@extends('layouts.app')

@section('content')
    <div class="container mx-auto pb-10">
        <!-- header section -->
        <div class="flex justify-between space-x-10">
            <div class="text-center mb-10 md:text-left md:w-9/12">
                <h2 class="text-xl font-bold text-gray-500 pb-4 dark:text-white">Stamp Duty Calculator</h2>
                <p class="wise1text dark:text-gray-300">This calculator looks at cost of stamp duty (name varies depending on region). Whilst care is taken to ensure this is 
                    updated as soon as possible following any changes you should be aware that it may take a few days to update after any budget so caution is advised if 
                    there has been a very recent change. Also be careful that higher amount are due when you already own other property. I have tried to highlight this as 
                    clearly as possible.</p>
            </div>
            <div class="hidden md:block md:w-3/12">
                <img class="h-[200px]" src="/assets/images/site/stamp-duty3.svg" alt="">
            </div>
        </div>
    <div class="border dark:border-gray-500 rounded p-10 shadow-lg">
        <!-- Show validation errors -->
        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-5">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Stamp Duty Form -->
        <form action="{{ route('stamp-duty.calculate') }}" method="get">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 dark:text-white">
                <!-- Scotland -->
                <div class="form-group">
                    <h3 class="text-lg font-bold mb-4">Scotland</h3>
                    <label>
                        <input type="checkbox" name="scot" {{ old('scot') ? 'checked' : '' }}> Standard Buyer
                    </label>
                    <label>
                        <input type="checkbox" name="scot_ftb" {{ old('scot_ftb') ? 'checked' : '' }}> First-Time Buyer
                    </label>
                </div>

                <!-- England -->
                <div class="form-group">
                    <h3 class="text-lg font-bold mb-4">England & Northern Ireland</h3>
                    <label>
                        <input type="checkbox" name="england" {{ old('england') ? 'checked' : '' }}> Standard Buyer
                    </label>
                    <label>
                        <input type="checkbox" name="england_ftb" {{ old('england_ftb') ? 'checked' : '' }}> First-Time Buyer
                    </label>
                </div>

                <!-- Wales -->
                <div class="form-group">
                    <h3 class="text-lg font-bold mb-4">Wales</h3>
                    <label>
                        <input type="checkbox" name="wales" {{ old('wales') ? 'checked' : '' }}> Standard Buyer
                    </label>
                    <label>
                        <input type="checkbox" name="wales_add" {{ old('wales_add') ? 'checked' : '' }}> Additional Property
                    </label>
                </div>
            </div>

            <div class="mt-6 w-4/12 mx-auto">
                <label for="amount" class="block mb-2 font-bold dark:text-white">Enter Purchase Price</label>
                <input type="number" name="amount" id="amount" class="w-full p-2 border rounded" value="{{ old('amount') }}" required>
                @error('amount')
                    <div class="text-red-600 mt-2">
                        {{ $message }}
                    </div>
                @enderror
                <button type="submit" class="mt-4 bg-lime-400 text-slate-800 hover:bg-lime-600 hover:text-white py-2 px-4 rounded">
                    Calculate
                </button>
            </div>
        </form>
    </div>

        @if(isset($region))
            <!-- Results Section -->
            <div class="mt-10 rounded dark:text-white">
                <h3 class="text-xl font-bold mb-4">
                    Tax Calculation for {{ $regionDisplayName }} - {{ $buyerType }}
                </h3>
                <table class="table-auto w-full text-left rounded">
                    <thead>
                        <tr class="bg-lime-300">
                            <th class=" dark:text-black">Band</th>
                            <th class=" dark:text-black">Start (£)</th>
                            <th class=" dark:text-black">End (£)</th>
                            <th class=" dark:text-black">Normal Rate (%)</th>
                            <th class=" dark:text-black">Normal Tax (£)</th>
                            <th class=" dark:text-black">Additional Rate (%)</th>
                            <th class=" dark:text-black">Additional Tax (£)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($region as $index => $band)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ number_format($band['start']) }}</td>
                                <td>{{ $band['end'] === PHP_INT_MAX ? '∞' : number_format($band['end']) }}</td>
                                <td>{{ isset($band['normalRate']) ? number_format($band['normalRate'] * 100) . '%' : 'N/A' }}</td>
                                <td>{{ number_format($band['normalTax'] ?? 0) }}</td>
                                <td>{{ isset($band['additionalRate']) ? number_format($band['additionalRate'] * 100) . '%' : 'N/A' }}</td>
                                <td>{{ number_format($band['additionalTax'] ?? 0) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="">
                        <tr class="font-bold">
                            <td colspan="4" class="text-right">Total:</td>
                            <td class="bg-lime-300 dark:text-black">{{ number_format($totalNormalTax) }}</td>
                            <td></td>
                            <td class="bg-lime-300 dark:text-black">{{ number_format($totalAdditionalTax) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <!-- Notes Section -->
            <div class="mt-10 dark:text-white">
                <h2 class="font-bold">Notes:</h2>
                <p>In scotland Stamp Duty is known as LBTT - Land & Bulding Transaction Tax </p>
                <p>In Wales Stamp Duty is known as LTT - Land Transaction Tax </p>
            </div>
        @endif
    </div>
@endsection