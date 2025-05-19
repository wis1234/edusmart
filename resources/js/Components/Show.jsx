// resources/js/Pages/Teachers/Show.jsx
import React from 'react';
import { Link } from '@inertiajs/inertia-react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/inertia-react';

export default function Show({ auth, teacher }) {
  return (
    <AuthenticatedLayout
      auth={auth}
      header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Teacher Details</h2>}
    >
      <Head title="Teacher Details" />

      <div className="py-12">
        <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div className="p-6 bg-white border-b border-gray-200">
              <div className="mb-6">
                <Link
                  href={route('teachers.index')}
                  className="text-blue-600 hover:underline"
                >
                  &larr; Back to Teachers List
                </Link>
              </div>

              <div className="mb-6">
                <h3 className="text-lg font-medium text-gray-900">Teacher Information</h3>
                <div className="mt-4 space-y-4">
                  <div>
                    <span className="text-gray-500">Name:</span>
                    <span className="ml-2 text-gray-900">{teacher.name}</span>
                  </div>
                  <div>
                    <span className="text-gray-500">Email:</span>
                    <span className="ml-2 text-gray-900">{teacher.email}</span>
                  </div>
                </div>
              </div>

              <div className="flex space-x-4">
                <Link
                  href={route('teachers.edit', teacher.id)}
                  className="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 active:bg-yellow-700 focus:outline-none focus:border-yellow-700 focus:ring ring-yellow-300 disabled:opacity-25 transition ease-in-out duration-150"
                >
                  Edit Teacher
                </Link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </AuthenticatedLayout>
  );
}