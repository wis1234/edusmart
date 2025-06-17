import React from 'react';
import { Head } from '@inertiajs/react';
import MainLayout from '@/Layouts/MainLayout';
import StudentForm from './Form';

export default function Create({ classRooms, schools, parents }) {
    return (
        <MainLayout>
            <Head title="Add New Student" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <StudentForm
                        classRooms={classRooms}
                        schools={schools}
                        parents={parents}
                    />
                </div>
            </div>
        </MainLayout>
    );
}
