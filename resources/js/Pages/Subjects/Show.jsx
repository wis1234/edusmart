import React from 'react';
import { InertiaLink } from '@inertiajs/inertia-react';

const SubjectsShow = ({ subject }) => {
  return (
    <div>
      <h1>Subject Details</h1>
      <InertiaLink href="/subjects" className="btn btn-secondary mb-4">
        Back to Subjects
      </InertiaLink>
      <div className="border p-4 rounded shadow">
        <p><strong>Name:</strong> {subject.name}</p>
        <p><strong>Code:</strong> {subject.code}</p>
        <p><strong>Description:</strong> {subject.description}</p>
        <p><strong>Credits:</strong> {subject.credits}</p>
        <p><strong>Level:</strong> {subject.level}</p>
        <p><strong>Hours per Week:</strong> {subject.hours_per_week}</p>
        <p><strong>Active:</strong> {subject.is_active ? 'Yes' : 'No'}</p>
      </div>
      <InertiaLink href={`/subjects/${subject.id}/edit`} className="btn btn-primary mt-4">
        Edit Subject
      </InertiaLink>
    </div>
  );
};

export default SubjectsShow;
