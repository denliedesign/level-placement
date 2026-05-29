<div style="font-family: Arial, sans-serif; max-width: 760px; margin: 0 auto;">
    <h1>MDU Class Favorites</h1>
    <p>Here are the classes you saved from the Family Flex Scheduler.</p>
    <p>Enrollment opens Saturday, June 6, 2026 at 8:00 AM. These favorites are here to help you prepare before enrollment opens.</p>

    <table style="border-collapse: collapse; width: 100%;">
        <thead>
        <tr>
            <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Style</th>
            <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Level</th>
            <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Day</th>
            <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Time</th>
        </tr>
        </thead>
        <tbody>
        @foreach($selectedClasses as $class)
            <tr>
                <td style="border: 1px solid #ddd; padding: 8px;"><strong>{{ $class->dance_style }}</strong></td>
                <td style="border: 1px solid #ddd; padding: 8px;">{{ $class->level }}</td>
                <td style="border: 1px solid #ddd; padding: 8px;">{{ $class->day_of_week }}</td>
                <td style="border: 1px solid #ddd; padding: 8px;">{{ $class->time }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <p>When enrollment opens, return to the studio portal to complete registration.</p>
</div>
