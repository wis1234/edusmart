import React from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link } from '@inertiajs/react';

export default function Dashboard({ auth, laravelVersion, phpVersion }) {
    return (
        <AuthenticatedLayout auth={auth} header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>}>
            <Head title="Dashboard" />
            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    {/* Stats Overview */}
                    <div className="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                        <div className="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                            <div className="p-6">
                                <div className="flex items-center">
                                    <div className="p-3 rounded-full bg-blue-100 dark:bg-blue-900">
                                        <svg className="w-6 h-6 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                    </div>
                                    <div className="ml-4">
                                        <h3 className="text-lg font-semibold text-gray-700 dark:text-gray-200">Total Students</h3>
                                        <p className="text-2xl font-bold text-gray-900 dark:text-white">1,234</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div className="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                            <div className="p-6">
                                <div className="flex items-center">
                                    <div className="p-3 rounded-full bg-green-100 dark:bg-green-900">
                                        <svg className="w-6 h-6 text-green-600 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                    </div>
                                    <div className="ml-4">
                                        <h3 className="text-lg font-semibold text-gray-700 dark:text-gray-200">Active Classes</h3>
                                        <p className="text-2xl font-bold text-gray-900 dark:text-white">42</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div className="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                            <div className="p-6">
                                <div className="flex items-center">
                                    <div className="p-3 rounded-full bg-purple-100 dark:bg-purple-900">
                                        <svg className="w-6 h-6 text-purple-600 dark:text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                        </svg>
                                    </div>
                                    <div className="ml-4">
                                        <h3 className="text-lg font-semibold text-gray-700 dark:text-gray-200">Courses</h3>
                                        <p className="text-2xl font-bold text-gray-900 dark:text-white">156</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div className="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                            <div className="p-6">
                                <div className="flex items-center">
                                    <div className="p-3 rounded-full bg-yellow-100 dark:bg-yellow-900">
                                        <svg className="w-6 h-6 text-yellow-600 dark:text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <div className="ml-4">
                                        <h3 className="text-lg font-semibold text-gray-700 dark:text-gray-200">Events Today</h3>
                                        <p className="text-2xl font-bold text-gray-900 dark:text-white">8</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* Quick Actions and Calendar */}
                    <div className="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                        <div className="lg:col-span-2 bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                            <div className="p-6">
                                <h3 className="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4">Quick Actions</h3>
                                <div className="grid grid-cols-2 md:grid-cols-3 gap-4">
                                    <Link href={route('teachers.index')} className="flex items-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition">
                                        <img src="https://cdn-icons-png.flaticon.com/512/1995/1995523.png" alt="Teacher" className="w-8 h-8 mr-3" />
                                        <span className="text-gray-700 dark:text-gray-200">Teachers</span>
                                    </Link>
                                    <Link href={route('students.index')} className="flex items-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition">
                                        <img src="https://cdn-icons-png.flaticon.com/512/1995/1995517.png" alt="Student" className="w-8 h-8 mr-3" />
                                        <span className="text-gray-700 dark:text-gray-200">Students</span>
                                    </Link>
                                    <Link href={route('class_rooms.index')} className="flex items-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition">
                                        <img src="https://cdn-icons-png.flaticon.com/512/1995/1995521.png" alt="Class Room" className="w-8 h-8 mr-3" />
                                        <span className="text-gray-700 dark:text-gray-200">Classes</span>
                                    </Link>
                                    <Link href={route('calendars.index')} className="flex items-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition">
                                        <img src="https://cdn-icons-png.flaticon.com/512/1995/1995527.png" alt="Calendar" className="w-8 h-8 mr-3" />
                                        <span className="text-gray-700 dark:text-gray-200">Calendar</span>
                                    </Link>
                                    <Link href={route('ecommerce.index')} className="flex items-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition">
                                        <img src="https://cdn-icons-png.flaticon.com/512/1170/1170576.png" alt="Ecommerce" className="w-8 h-8 mr-3" />
                                        <span className="text-gray-700 dark:text-gray-200">Ecommerce</span>
                                    </Link>
                                </div>
                            </div>
                        </div>

                        <div className="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                            <div className="p-6">
                                <h3 className="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4">Upcoming Events</h3>
                                <div className="space-y-4">
                                    <div className="flex items-start">
                                        <div className="flex-shrink-0 w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                                            <span className="text-blue-600 dark:text-blue-300 font-semibold">15</span>
                                        </div>
                                        <div className="ml-4">
                                            <h4 className="text-sm font-medium text-gray-900 dark:text-white">Math Exam</h4>
                                            <p className="text-sm text-gray-500 dark:text-gray-400">9:00 AM - 11:00 AM</p>
                                        </div>
                                    </div>
                                    <div className="flex items-start">
                                        <div className="flex-shrink-0 w-12 h-12 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                                            <span className="text-green-600 dark:text-green-300 font-semibold">16</span>
                                        </div>
                                        <div className="ml-4">
                                            <h4 className="text-sm font-medium text-gray-900 dark:text-white">Science Project Due</h4>
                                            <p className="text-sm text-gray-500 dark:text-gray-400">All Day</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {/* Recent Activity */}
                    <div className="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                        <div className="p-6">
                            <h3 className="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4">Recent Activity</h3>
                            <div className="space-y-4">
                                <div className="flex items-center">
                                    <div className="flex-shrink-0">
                                        <div className="w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-700"></div>
                                    </div>
                                    <div className="ml-4">
                                        <p className="text-sm text-gray-600 dark:text-gray-400">
                                            <span className="font-medium text-gray-900 dark:text-white">John Doe</span> submitted Math assignment
                                        </p>
                                        <p className="text-xs text-gray-500">2 hours ago</p>
                                    </div>
                                </div>
                                <div className="flex items-center">
                                    <div className="flex-shrink-0">
                                        <div className="w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-700"></div>
                                    </div>
                                    <div className="ml-4">
                                        <p className="text-sm text-gray-600 dark:text-gray-400">
                                            <span className="font-medium text-gray-900 dark:text-white">Jane Smith</span> graded Science project
                                        </p>
                                        <p className="text-xs text-gray-500">4 hours ago</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
