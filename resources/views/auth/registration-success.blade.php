<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Success</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="min-h-screen flex flex-col items-center justify-center bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50">
    <div class="bg-white rounded-lg shadow-lg p-8 max-w-lg w-full text-center">
        <div class="flex justify-center mb-6">
            <svg class="w-16 h-16 text-green-500 animate-bounce" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2l4-4m6 2a9 9 0 11-18 0a9 9 0 0118 0z" />
            </svg>
        </div>
        <h1 class="text-3xl font-bold text-gray-900 mb-4">Registration Successful</h1>
        <p class="text-lg text-gray-700 mb-6">
            Thank you for registering on our platform.<br>
            <span class="font-semibold text-blue-700">Your account is currently under review for validation by our team.</span><br>
            You will receive an email notification once your account is validated and you can proceed to log in.<br><br>
            <span class="text-gray-800 font-medium">For your security:</span><br>
            <span class="text-gray-600">You will have <span class="font-bold text-red-600">three</span> attempts to log in. After three unsuccessful attempts, your account will be temporarily locked for your protection. If this happens, please follow the instructions in the email you will receive to unlock your account or contact support.</span>
        </p>
        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <a href="/login" class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition duration-200 font-semibold">Go to Login</a>
            <a href="/" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200 font-semibold">Back to Home</a>
        </div>
    </div>
</body>
</html> 