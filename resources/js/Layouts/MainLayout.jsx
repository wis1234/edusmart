import React from 'react';
import { Link, usePage } from '@inertiajs/react';

export default function MainLayout({ children, title }) {
    const { auth } = usePage().props;

    return (
        <div className="min-h-screen bg-gray-100">
            <nav className="bg-white border-b border-gray-200">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="flex justify-between h-16">
                        <div className="flex">
                            <div className="flex-shrink-0 flex items-center">
                                <Link href="/" className="text-xl font-bold text-gray-800">
                                    EduSmart
                                </Link>
                            </div>
                            <div className="hidden sm:ml-6 sm:flex sm:space-x-8">
                                <Link
                                    href="/dashboard"
                                    className="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium"
                                >
                                    Dashboard
                                </Link>
                                {/* Add more navigation links as needed */}
                            </div>
                        </div>
                        
                        <div className="flex items-center">
                            {auth?.user ? (
                                <div className="flex items-center space-x-4">
                                    <span className="text-sm text-gray-700">{auth.user.name}</span>
                                    <Link
                                        href="/logout"
                                        method="post"
                                        as="button"
                                        className="text-sm text-gray-700 hover:text-gray-900"
                                    >
                                        Logout
                                    </Link>
                                </div>
                            ) : (
                                <div className="space-x-4">
                                    <Link
                                        href="/login"
                                        className="text-sm text-gray-700 hover:text-gray-900"
                                    >
                                        Login
                                    </Link>
                                    <Link
                                        href="/register"
                                        className="text-sm text-gray-700 hover:text-gray-900"
                                    >
                                        Register
                                    </Link>
                                </div>
                            )}
                        </div>
                    </div>
                </div>
            </nav>

            <main className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    {title && (
                        <h1 className="text-3xl font-bold text-gray-900 mb-6">
                            {title}
                        </h1>
                    )}
                    {children}
                </div>
            </main>
        </div>
    );
} 