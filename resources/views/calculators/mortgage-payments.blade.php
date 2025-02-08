@extends('layouts.app')

@section('content')

<div class="">

    <div class="flex justify-between space-x-10">
        <div class="text-center mb-10 md:text-left md:w-9/12">
            <h2 class="text-xl font-bold text-gray-500 pb-4 dark:text-white">Mortgage Calculator</h2>
            <p class="wise1text dark:text-gray-300">This mortgage calculator will give you a good understanding of what you will pay based on the interest rate for the mortgage product you are considering. It gives both interest only and repayment figures. Given the requirement for lenders to stress mortgage payments to ensure you can afford the payments in the event of a rate increase, I have provided an indication of what this could be. You will need to ensure you can afford the mortgage at the stressed rate.</p>
        </div>
        <div class="hidden w-3/12 md:block">
            <img class="h-[200px]" src="/assets/images/site/mortgage-calculator.svg" alt="">
        </div>
    </div>

    <div role="main">
        <div class="mb-10 border dark:border-gray-600 rounded shadow-lg border-gray-300 p-4 text-sm">
            <h3 class="text-center font-semibold text-gray-600 pb-4 dark:text-gray-200">Complete the form below to view the results.</h3>

            <!-- Add ID to the form field that you want to format -->
            <form class="flex flex-col py-4 space-y-8 md:flex-row md:space-y-0 md:justify-evenly" action="/calculators/mortgage-payments" method="get">
                @csrf
                <div>
                    <input type="text" class="border border-gray-300 p-2 w-full" name="amount" id="amount" placeholder="Mortgage amount" value="{{ old('amount') }}">
                </div>
                <div>
                    <input type="text" class="border border-gray-300 p-2 w-full" name="term" id="term" placeholder="Enter Term in years" value="{{ old('term') }}">
                </div>
                <div>
                    <input type="text" class="border border-gray-300 p-2 w-full" name="rate" id="rate" placeholder="Enter rate (i.e 2.99)" value="{{ old('rate') }}">
                </div>
                <button class="bg-lime-400 text-slate-800 rounded hover:bg-lime-600 hover:text-white font-semibold text-sm w-1/4 md:w-20
                                 dark:bg-gray-500 dark:hover:bg-gray-300 dark:text-gray-200 dark:hover:text-gray-700 py-2" type="submit" name="submit">SUBMIT</button>
            </form>
        </div>
        <div>

            @if(isset($amount) && isset($term) && isset($rate))
                <div class="mt-10">
                    <div class="my-10">
                        <h4 class="text-center font-bold border rounded shadow-lg border-gray-300 dark:border-gray-600 text-lg p-4 dark:text-white">Based on a loan of <span class="text-lime-600">£{{ number_format($amount) }}</span> over <span class="text-lime-600">{{ $term }}</span> years at a rate of <span class="text-lime-600">{{ number_format($rate, 2) }}%</span></h4>
                    </div>
                    <div class="flex flex-col space-y-10 md:flex-row md:space-y-0 md:justify-around my-10">
                        <div class="text-center w-full md:w-1/3 border border-gray-300 rounded shadow-lg p-4 bg-yellow-100">
                            <h2 class="text-lg font-bold mb-4">Interest Only</h2>
                            <p class="">Each <span class="font-bold text-lime-600 underline">month</span> you will repay: <span class="link-color"> £{{ number_format($interest_only / 12) }}</span></p>
                            <p class="">Each <span class="font-bold text-lime-600 underline">year</span> you will repay: <span class="link-color"> £{{ number_format($interest_only) }}</span></p>                            
                            <p class="text-sm my-4 text-gray-600">With interest only you don't pay anything to the capital balance over the term. <span class="underline text-orange-700">At the end of {{ number_format($term) }} years you will still owe the lender £{{ number_format($amount) }}.</span></p>
                            <p class="text-sm mt-4 text-gray-600">All lenders will expect you to have a strategy that you can evidence at the time of application demonstrating you have the means to repay the £{{ number_format($amount) }} when the term ends.  In most cases this will be from selling the property.  Other repayment strategies may be available from the lender.</p>                   
                        </div>
                        <div class="text-center w-full md:w-1/3 border border-gray-300 rounded shadow-lg p-4 bg-yellow-100">
                            <h2 class="text-lg font-bold mb-4">Repayment</h2>
                            <p class="">Each <span class="font-bold text-lime-600 underline">month</span> you will repay: <span class="link-color"> £{{ number_format($repayment) }}</span></p>
                            <p>Each <span class="font-bold text-lime-600 underline">year</span> you will repay: <span class="link-color"> £{{ number_format($repayment * 12) }}</span></p>
                            <p class="my-4 text-sm text-gray-600">Repayment is the most common type of mortgage. Each month your payment is split, some to the interest and some to the capital. When the {{ number_format($term) }} year term is complete you know that the mortgage is fully repaid and the house is yours.</p>
                            <p class="text-sm text-gray-600">In the early years the bulk of your payment will be put towards the interest, gradually that changes and you will pay more to the capital as the balance reduces.</p>
                            <p class="text-sm text-gray-600">After making your last payment the total amount paid over {{ number_format($term) }} years will be £{{ number_format(($repayment * 12) * $term) }}.</p>
                            <p class="mt-4 text-sm text-gray-600">For every £1 borrowed you will repay £{{ number_format((($repayment * 12) * $term) / $amount, 2) }}</p>                       
                        </div>
                    </div>
                    <div class="my-10 border-gray-300 dark:border-gray-600 rounded shadow-lg">
                    </div>
                </div>
            @elseif (isset($_GET['submit']) && (empty($amount) || empty($term) || empty($rate)))
                <p class="text-center py-4 text-red-500 font-bold">You have submitted the form without all three values, please try again.</p>
            @endif
        </div>
    </div>
</div>

<!-- JavaScript for formatting number input -->
<script>
    document.getElementById('amount').addEventListener('input', function (e) {
        // Remove any existing commas
        let value = e.target.value.replace(/,/g, '');

        // Check if it's a valid number
        if (!isNaN(value) && value.length > 0) {
            // Add commas using toLocaleString()
            e.target.value = parseFloat(value).toLocaleString();
        }
    });
</script>

@endsection