import React from 'react';
import { Head } from '@inertiajs/react';
import MainLayout from '@/Layouts/MainLayout';
import StudentForm from './Form';

export default function Edit({ student, classRooms, schools, parents }) {
    return (
        <MainLayout>
            <Head title={`Edit Student: ${student.first_name} ${student.last_name}`} />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <StudentForm
                        student={student}
                        classRooms={classRooms}
                        schools={schools}
                        parents={parents}
                    />
                </div>
            </div>
        </MainLayout>
    );
}
