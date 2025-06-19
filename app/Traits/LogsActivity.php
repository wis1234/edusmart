<?php

namespace App\Traits;

use App\Models\Activity;

trait LogsActivity
{
    protected static function bootLogsActivity()
    {
        static::created(function ($model) {
            $attributes = $model->getAttributes();
            $details = [];

            $fieldLabels = self::fieldLabels();

            foreach ($attributes as $key => $value) {
                if (!in_array($key, ['created_at', 'updated_at', 'id']) && !is_null($value)) {
                    $label = $fieldLabels[$key] ?? ucfirst(str_replace('_', ' ', $key));
                    $details[] = "$label: $value";
                }
            }

            static::logActivity(
                'create',
                "Created new " . class_basename($model) . " with details: " . implode(', ', $details),
                $model
            );
        });

        static::updated(function ($model) {
            $changes = $model->getDirty();
            $changeDescription = [];

            $fieldLabels = self::fieldLabels();

            foreach ($changes as $attribute => $value) {
                if (!in_array($attribute, ['updated_at'])) {
                    $oldValue = $model->getOriginal($attribute);
                    if (is_null($oldValue)) $oldValue = 'null';
                    if (is_null($value)) $value = 'null';

                    // Format date if casted
                    if ($model->hasCast($attribute, ['date', 'datetime'])) {
                        $oldValue = $oldValue ? date('Y-m-d', strtotime($oldValue)) : 'null';
                        $value = $value ? date('Y-m-d', strtotime($value)) : 'null';
                    }

                    // Password: hide actual value
                    if ($attribute === 'password') {
                        $changeDescription[] = "The password has been updated to reinforce security.";
                        continue;
                    }

                    // First or Last Name
                    if (in_array($attribute, ['first_name', 'last_name'])) {
                        $label = $fieldLabels[$attribute] ?? ucfirst(str_replace('_', ' ', $attribute));
                        $changeDescription[] = "$label changed from '$oldValue' to '$value'";
                        continue;
                    }

                    // Profile photo
                    if ($attribute === 'profile_photo') {
                        $changeDescription[] = "The profile photo has been updated.";
                        continue;
                    }

                    $label = $fieldLabels[$attribute] ?? ucfirst(str_replace('_', ' ', $attribute));
                    $changeDescription[] = "$label changed from '$oldValue' to '$value'";
                }
            }

            if (!empty($changeDescription)) {
                $modelName = class_basename($model);
                $identifier = $model->name ?? $model->title ?? $model->id;

                static::logActivity(
                    'update',
                    "Updated $modelName #$identifier with changes: " . implode('; ', $changeDescription),
                    $model,
                    $changeDescription
                );
            }
        });

        static::deleted(function ($model) {
            $modelName = class_basename($model);
            $identifier = $model->name ?? $model->title ?? $model->id;

            static::logActivity(
                'delete',
                "Deleted $modelName #$identifier",
                $model
            );
        });
    }

    protected static function logActivity($type, $description, $model = null, $customChanges = null)
    {
        $data = [
            'user_id' => auth()->id() ?? null,
            'type' => $type,
            'description' => $description,
            'related_type' => get_called_class(),
            'related_id' => $model ? $model->getKey() : null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ];

        if ($type === 'update' && $model) {
            $data['details'] = json_encode([
                'changes' => $customChanges ?? [],
                'changed_by' => auth()->user() ? (auth()->user()->first_name . ' ' . auth()->user()->last_name) : 'System'
            ]);
        }

        Activity::create($data);
    }

    // ✅ Field label mapping (add more as needed)
    protected static function fieldLabels(): array
    {
        return [
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'email' => 'Email Address',
            'phone' => 'Phone Number',
            'profile_photo' => 'Profile Picture',
            'status' => 'Status',
            'address' => 'Address',
            // Add more fields as needed...
        ];
    }
}
