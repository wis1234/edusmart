@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <!-- Header Section -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-6 py-4">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center">
                <div class="mb-4 md:mb-0">
                    <h1 class="text-2xl font-bold text-white">📈 Student Performance Analytics</h1>
                    <p class="text-blue-100 mt-1">
                        {{ $evaluation->subject->name }} - {{ $evaluation->evaluationType->name }}
                    </p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('evaluations.student_grades.create', $evaluation) }}" 
                       class="inline-flex items-center bg-white/20 hover:bg-white/30 text-white font-medium px-4 py-2 rounded-lg shadow-sm transition duration-200 backdrop-blur-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Grade a Student
                    </a>
                </div>
            </div>
        </div>

        <!-- Performance Insights Section -->
        <div class="p-6 border-b border-gray-200 bg-gray-50">
            <h2 class="text-lg font-semibold text-gray-800 mb-3">📊 Key Performance Trends</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 text-green-600 mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Consistent Improvers</p>
                            <p class="text-xl font-semibold">{{ $performanceMetrics['consistent_improvers'] }} Students</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-yellow-100 text-yellow-600 mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Inconsistent Performers</p>
                            <p class="text-xl font-semibold">{{ $performanceMetrics['inconsistent_performers'] }} Students</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-red-100 text-red-600 mr-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Declining Performance</p>
                            <p class="text-xl font-semibold">{{ $performanceMetrics['declining_performers'] }} Students</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Student Performance Table -->
        <div class="p-6">
            @if($grades->isEmpty())
                <div class="text-center py-8">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <h3 class="mt-2 text-lg font-medium text-gray-900">No grades recorded yet</h3>
                    <p class="mt-1 text-gray-500">Get started by grading a student.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Student
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Current Grade
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Performance Trend
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Growth Analysis
                                </th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($grades as $grade)
                            @php
                                $performance = $grade->getPerformanceTrend();
                                $trendColor = [
                                    'improving' => 'text-green-600 bg-green-50',
                                    'declining' => 'text-red-600 bg-red-50',
                                    'consistent' => 'text-yellow-600 bg-yellow-50',
                                    'new' => 'text-blue-600 bg-blue-50'
                                ][$performance['trend']];
                                
                                $improvementPercentage = $performance['improvement_percentage'] ?? 0;
                            @endphp
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                            <span class="text-blue-600 font-medium">
                                                {{ substr($grade->student->first_name, 0, 1) }}{{ substr($grade->student->last_name, 0, 1) }}
                                            </span>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $grade->student->first_name }} {{ $grade->student->last_name }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $grade->student->admission_number }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $grade->marks_obtained }} / {{ $evaluation->total_marks }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        @php
                                            $percentage = ($grade->marks_obtained / $evaluation->total_marks) * 100;
                                            $status = $percentage >= ($evaluation->passing_marks / $evaluation->total_marks * 100) ? 'Pass' : 'Fail';
                                        @endphp
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $status === 'Pass' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ round($percentage) }}% ({{ $status }})
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        @if($performance['trend'] == 'improving')
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                                        </svg>
                                        <span class="{{ $trendColor }} px-2 py-1 rounded-full text-xs font-medium">
                                            Improving (+{{ $improvementPercentage }}%)
                                        </span>
                                        @elseif($performance['trend'] == 'declining')
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                                        </svg>
                                        <span class="{{ $trendColor }} px-2 py-1 rounded-full text-xs font-medium">
                                            Declining ({{ $improvementPercentage }}%)
                                        </span>
                                        @elseif($performance['trend'] == 'consistent')
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-500 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6" />
                                        </svg>
                                        <span class="{{ $trendColor }} px-2 py-1 rounded-full text-xs font-medium">
                                            Consistent ({{ $improvementPercentage }}%)
                                        </span>
                                        @else
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                        </svg>
                                        <span class="{{ $trendColor }} px-2 py-1 rounded-full text-xs font-medium">
                                            New Student
                                        </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">
                                        @if($performance['trend'] != 'new')
                                        <div class="flex items-center mb-1">
                                            <span class="w-24 text-xs text-gray-500 mr-2">Last Evaluation:</span>
                                            <span class="text-sm font-medium">{{ $performance['previous_grade'] ?? 'N/A' }}</span>
                                        </div>
                                        <div class="flex items-center">
                                            <span class="w-24 text-xs text-gray-500 mr-2">Improvement:</span>
                                            <span class="text-sm font-medium {{ $improvementPercentage > 0 ? 'text-green-600' : 'text-red-600' }}">
                                                {{ $improvementPercentage > 0 ? '+' : '' }}{{ $improvementPercentage }}%
                                            </span>
                                        </div>
                                        @else
                                        <span class="text-sm text-gray-500">First evaluation for this student</span>
                                        @endif
                                    </div>
                                </td>
<td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
    <a href="{{ route('student_grades.edit', $grade) }}" 
       class="text-blue-600 hover:text-blue-900 mr-4 inline-flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
        </svg>
        Edit
    </a>
    <!-- <form action="{{ route('student_grades.destroy', $grade) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this grade?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="text-red-600 hover:text-red-900 inline-flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
            Delete
        </button>
    </form> -->
</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Performance Visualization -->
                <div class="mt-8 bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">📈 Class Performance Over Time</h3>
                    <div class="h-64">
                        <!-- This would be replaced with an actual chart library like Chart.js -->
                        <div class="flex items-center justify-center h-full bg-gray-50 rounded-lg border border-gray-200">
                            <p class="text-gray-500">Performance trend visualization would appear here</p>
                        </div>
                    </div>
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="p-3 bg-blue-50 rounded-lg">
                            <h4 class="text-sm font-medium text-blue-800 mb-1">Top Improver</h4>
                            <p class="text-lg font-semibold">{{ $performanceMetrics['top_improver']['firstname'] ?? 'N/A' }}</p>
                            <p class="text-sm text-blue-600">+{{ $performanceMetrics['top_improver']['improvement'] ?? 0 }}% growth</p>
                        </div>
                        <div class="p-3 bg-green-50 rounded-lg">
                            <h4 class="text-sm font-medium text-green-800 mb-1">Highest Score</h4>
                            <p class="text-lg font-semibold">{{ $performanceMetrics['highest_score']['name'] ?? 'N/A' }}</p>
                            <p class="text-sm text-green-600">{{ $performanceMetrics['highest_score']['score'] ?? 0 }}%</p>
                        </div>
                        <div class="p-3 bg-yellow-50 rounded-lg">
                            <h4 class="text-sm font-medium text-yellow-800 mb-1">Needs Attention</h4>
                            <p class="text-lg font-semibold">{{ $performanceMetrics['needs_attention']['name'] ?? 'N/A' }}</p>
                            <p class="text-sm text-yellow-600">{{ $performanceMetrics['needs_attention']['decline'] ?? 0 }}% decline</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection