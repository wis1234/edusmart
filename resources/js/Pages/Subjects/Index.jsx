import React from 'react';
import { InertiaLink } from '@inertiajs/inertia-react';

const SubjectsIndex = ({ subjects }) => {
  return (
    <div>
      <h1>Subjects</h1>
      <InertiaLink href="/subjects/create" className="btn btn-primary mb-4">
        Create New Subject
      </InertiaLink>
      <table className="table-auto w-full border-collapse border border-gray-200">
        <thead>
          <tr>
            <th className="border border-gray-300 px-4 py-2">Name</th>
            <th className="border border-gray-300 px-4 py-2">Code</th>
            <th className="border border-gray-300 px-4 py-2">Credits</th>
            <th className="border border-gray-300 px-4 py-2">Level</th>
            <th className="border border-gray-300 px-4 py-2">Actions</th>
          </tr>
        </thead>
        <tbody>
          {subjects.data.map((subject) => (
            <tr key={subject.id}>
              <td className="border border-gray-300 px-4 py-2">{subject.name}</td>
              <td className="border border-gray-300 px-4 py-2">{subject.code}</td>
              <td className="border border-gray-300 px-4 py-2">{subject.credits}</td>
              <td className="border border-gray-300 px-4 py-2">{subject.level}</td>
              <td className="border border-gray-300 px-4 py-2">
                <InertiaLink href={`/subjects/${subject.id}`} className="text-blue-600 hover:underline mr-2">
                  View
                </InertiaLink>
                <InertiaLink href={`/subjects/${subject.id}/edit`} className="text-green-600 hover:underline">
                  Edit
                </InertiaLink>
              </td>
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
};

export default SubjectsIndex;
