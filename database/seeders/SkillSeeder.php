<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Skill;
use Illuminate\Support\Str;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $skills = [
            'PHP', 'Laravel', 'JavaScript', 'React', 'Vue.js', 'Angular',
            'Node.js', 'Python', 'Java', 'C#', '.NET', 'MySQL',
            'PostgreSQL', 'MongoDB', 'Redis', 'Docker', 'Kubernetes',
            'AWS', 'Azure', 'Google Cloud', 'HTML', 'CSS', 'Bootstrap',
            'Tailwind CSS', 'Git', 'GitHub', 'GitLab', 'CI/CD',
            'Photoshop', 'Illustrator', 'Figma', 'UI/UX Design',
            'Marketing Digital', 'SEO', 'Content Marketing', 'Social Media',
            'Excel', 'Word', 'PowerPoint', 'Tiếng Anh', 'Tiếng Nhật',
            'Tiếng Hàn', 'Tiếng Trung', 'Kỹ năng giao tiếp', 'Làm việc nhóm',
            'Quản lý thời gian', 'Giải quyết vấn đề',
        ];

        foreach ($skills as $skill) {
            Skill::create([
                'name' => $skill,
                'slug' => Str::slug($skill),
            ]);
        }
    }
}
