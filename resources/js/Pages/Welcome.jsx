import { Head, Link } from '@inertiajs/react';

export default function Welcome({ auth, laravelVersion, phpVersion }) {
    return (
        <>
            <Head title="Welcome" />
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

                    <section className="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div className="bg-white dark:bg-gray-800 rounded-lg shadow p-6 flex flex-col items-center text-center">
                            <img src="https://cdn-icons-png.flaticon.com/512/1995/1995523.png" alt="Teacher" className="w-24 h-24 mb-4" />
                            <h2 className="text-2xl font-semibold mb-2">Teachers</h2>
                            <p className="text-gray-600 dark:text-gray-400">
                                Manage your classes, students, and schedules efficiently.
                            </p>
                        </div>
                        <div className="bg-white dark:bg-gray-800 rounded-lg shadow p-6 flex flex-col items-center text-center">
                            <img src="https://cdn-icons-png.flaticon.com/512/1995/1995519.png" alt="Parent" className="w-24 h-24 mb-4" />
                            <h2 className="text-2xl font-semibold mb-2">Parents</h2>
                            <p className="text-gray-600 dark:text-gray-400">
                                Stay connected with your child's progress and school activities.
                            </p>
                        </div>
                        <div className="bg-white dark:bg-gray-800 rounded-lg shadow p-6 flex flex-col items-center text-center">
                            <img src="https://cdn-icons-png.flaticon.com/512/1995/1995517.png" alt="Student" className="w-24 h-24 mb-4" />
                            <h2 className="text-2xl font-semibold mb-2">Students</h2>
                            <p className="text-gray-600 dark:text-gray-400">
                                Access your classes, assignments, and calendar all in one place.
                            </p>
                        </div>
                        <div className="bg-white dark:bg-gray-800 rounded-lg shadow p-6 flex flex-col items-center text-center">
                            <img src="https://cdn-icons-png.flaticon.com/512/1995/1995521.png" alt="Administrator" className="w-24 h-24 mb-4" />
                            <h2 className="text-2xl font-semibold mb-2">Administrators</h2>
                            <p className="text-gray-600 dark:text-gray-400">
                                Oversee school operations, manage users, and maintain smooth workflows.
                            </p>
                        </div>
                    </section>

                    <section className="mt-10 text-center">
                        {!auth.user ? (
                            <>
                                <Link
                                    href={route('login')}
                                    className="inline-block rounded bg-[gray] px-6 py-3 text-white font-semibold hover:bg-[black] transition"
                                >
                                    Log In
                                </Link>
                                <Link
                                    href={route('register')}
                                    className="ml-4 inline-block rounded border border-[#FF2D20] px-6 py-3 text-[#FF2D20] font-semibold hover:bg-[#FF2D20] hover:text-white transition"
                                >
                                    Register
                                </Link>
                            </>
                        ) : (
                            <Link
                                href={route('dashboard')}
                                className="inline-block rounded bg-[#FF2D20] px-6 py-3 text-white font-semibold hover:bg-[#e0261c] transition"
                            >
                                Go to Dashboard
                            </Link>
                        )}
                    </section>

                    <footer className="text-center text-sm text-gray-500 dark:text-gray-400 mt-12">
                        EduSmart v1.2.0
                    </footer>
                </div>
            </div>
        </>
    );
}
