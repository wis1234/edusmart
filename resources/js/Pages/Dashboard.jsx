import React from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link } from '@inertiajs/react';

export default function Dashboard({ auth, laravelVersion, phpVersion }) {
    return (
        <AuthenticatedLayout auth={auth} header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>}>
            <Head title="Dashboard" />
            <div className="bg-gray-50 text-gray-900 dark:bg-gray-900 dark:text-gray-100 min-h-screen flex flex-col items-center justify-center px-6 py-12">
                <div className="max-w-4xl w-full space-y-8">
                    <header className="text-center">
                        <h1 className="text-4xl font-extrabold mb-4">
                            Welcome to EduSmart
                        </h1>
                        <p className="text-lg text-gray-600 dark:text-gray-400">
                            A comprehensive education management system tailored for Teachers, Parents, Students, and School Administrators.
                        </p>
                    </header>

                    <section className="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <Link href={route('teachers.index')} className="block">
                            <div className="bg-white dark:bg-gray-800 rounded-lg shadow p-6 flex flex-col items-center text-center hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                <img src="https://cdn-icons-png.flaticon.com/512/1995/1995523.png" alt="Teacher" className="w-24 h-24 mb-4" />
                                <h2 className="text-2xl font-semibold mb-2">Teachers</h2>
                                <p className="text-gray-600 dark:text-gray-400">
                                    Manage your classes, students, and schedules efficiently.
                                </p>
                            </div>
                        </Link>
                        <Link href={route('parents.index')} className="block">
                            <div className="bg-white dark:bg-gray-800 rounded-lg shadow p-6 flex flex-col items-center text-center hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                <img src="https://cdn-icons-png.flaticon.com/512/1995/1995519.png" alt="Parent" className="w-24 h-24 mb-4" />
                                <h2 className="text-2xl font-semibold mb-2">Parents</h2>
                                <p className="text-gray-600 dark:text-gray-400">
                                    Stay connected with your child's progress and school activities.
                                </p>
                            </div>
                        </Link>
                        <Link href={route('students.index')} className="block">
                            <div className="bg-white dark:bg-gray-800 rounded-lg shadow p-6 flex flex-col items-center text-center hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                <img src="https://cdn-icons-png.flaticon.com/512/1995/1995517.png" alt="Student" className="w-24 h-24 mb-4" />
                                <h2 className="text-2xl font-semibold mb-2">Students</h2>
                                <p className="text-gray-600 dark:text-gray-400">
                                    Access your classes, assignments, and calendar all in one place.
                                </p>
                            </div>
                        </Link>
                        <Link href={route('class_rooms.index')} className="block">
                            <div className="bg-white dark:bg-gray-800 rounded-lg shadow p-6 flex flex-col items-center text-center hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                <img src="https://cdn-icons-png.flaticon.com/512/1995/1995521.png" alt="Class Room" className="w-24 h-24 mb-4" />
                                <h2 className="text-2xl font-semibold mb-2">Class Rooms</h2>
                                <p className="text-gray-600 dark:text-gray-400">
                                    Manage classrooms, schedules, and student assignments.
                                </p>
                            </div>
                        </Link>
                        <Link href={route('schools.index')} className="block">
                            <div className="bg-white dark:bg-gray-800 rounded-lg shadow p-6 flex flex-col items-center text-center hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                <img src="https://cdn-icons-png.flaticon.com/512/1995/1995525.png" alt="School" className="w-24 h-24 mb-4" />
                                <h2 className="text-2xl font-semibold mb-2">Schools</h2>
                                <p className="text-gray-600 dark:text-gray-400">
                                    Manage school information and administrative tasks.
                                </p>
                            </div>
                        </Link>
                        <Link href={route('calendars.index')} className="block">
                            <div className="bg-white dark:bg-gray-800 rounded-lg shadow p-6 flex flex-col items-center text-center hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                <img src="https://cdn-icons-png.flaticon.com/512/1995/1995527.png" alt="Calendar" className="w-24 h-24 mb-4" />
                                <h2 className="text-2xl font-semibold mb-2">Calendar</h2>
                                <p className="text-gray-600 dark:text-gray-400">
                                    View and manage school events and schedules.
                                </p>
                            </div>
                        </Link>
                    </section>

                    {/* <section className="mt-10 text-center">
                        <Link
                            href="/profile"
                            className="inline-block rounded bg-[#FF2D20] px-6 py-3 text-white font-semibold hover:bg-[#e0261c] transition"
                        >
                            Log out
                        </Link>
                    </section> */}

                    <footer className="text-center text-sm text-gray-500 dark:text-gray-400 mt-12">
                        EduSmart v1.2.0
                    </footer>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
