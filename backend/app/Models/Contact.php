<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'project_type',
        'budget',
        'message',
        'status',
        'replied_at',
        'notes',
    ];

    protected $casts = [
        'replied_at' => 'datetime',
    ];

    protected $appends = [
        'formatted_status',
        'is_archived',
    ];

    /**
     * Get the formatted status label.
     */
    protected function formattedStatus(): Attribute
    {
        return Attribute::make(
            get: fn () => match ($this->status) {
                'new' => 'New',
                'in_progress' => 'In Progress',
                'replied' => 'Replied',
                'archived' => 'Archived',
                default => ucfirst($this->status ?? 'New'),
            }
        );
    }

    /**
     * Check if the contact is archived.
     */
    protected function isArchived(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->status === 'archived'
        );
    }

    /**
     * Get the human-readable project type.
     */
    protected function projectTypeLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => match ($this->project_type) {
                'web' => 'Web Application',
                'mobile' => 'Mobile App',
                'saas' => 'SaaS Product',
                'api' => 'API Development',
                'consulting' => 'Consulting',
                'other' => 'Other',
                default => ucfirst($this->project_type ?? 'Not specified'),
            }
        );
    }

    /**
     * Get the human-readable budget range.
     */
    protected function budgetLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => match ($this->budget) {
                'small' => '$1,000 - $5,000',
                'medium' => '$5,000 - $20,000',
                'large' => '$20,000 - $50,000',
                'enterprise' => '$50,000+',
                'not-sure' => 'Not sure yet',
                default => 'Not specified',
            }
        );
    }

    /**
     * Scope for new contacts.
     */
    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }

    /**
     * Scope for contacts needing reply.
     */
    public function scopeNeedsReply($query)
    {
        return $query->whereIn('status', ['new', 'in_progress']);
    }

    /**
     * Scope for archived contacts.
     */
    public function scopeArchived($query)
    {
        return $query->where('status', 'archived');
    }

    /**
     * Scope for recent contacts (last 7 days).
     */
    public function scopeRecent($query)
    {
        return $query->where('created_at', '>=', now()->subDays(7));
    }
}
