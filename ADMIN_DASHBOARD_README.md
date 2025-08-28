# Admin Dashboard for Katsu Anime Platform

This document explains how to use the newly created admin dashboard for managing your anime and episodes.

## Features

### 🎯 Dashboard Overview
- Statistics overview (total anime, episodes, users)
- Recent anime and episodes
- Quick action buttons

### 📺 Anime Management
- **List View**: Searchable table with filters
- **Create/Edit**: Complete forms with image uploads
- **Tags**: Searchable tag system
- **Status Management**: Draft, Ongoing, Completed, Upcoming, Cancelled
- **Visibility Control**: Public, Private, Draft

### 🎬 Episode Management  
- **List View**: Searchable table with anime filtering
- **Create/Edit**: Comprehensive forms with multiple file uploads
- **Video Upload**: 720p video files with upload progress indicator
- **Subtitle Support**: Mongolian and English subtitle files (.vtt, .srt)
- **Scheduling**: Schedule episodes for future release
- **Status Management**: Draft, Scheduled, Published, Archived
- **Visibility Control**: Public, Private, Premium

## Getting Started

### 1. Access the Admin Dashboard
Navigate to: `http://your-domain.com/admin`

### 2. Navigation
The sidebar provides easy access to:
- Dashboard (statistics and overview)
- Anime management
- Episode management
- Back to main site

## Creating Content

### Creating an Anime

1. Go to **Admin → Anime → Add New Anime**
2. Fill in the required fields:
   - **Title** (required)
   - **Category** (required) 
   - **Status** (required)
   - **Visibility** (required)
3. Optional fields:
   - English/Japanese titles
   - Total episodes
   - Cover image and poster
   - Tags (searchable)
   - Published date
   - Featured status

### Creating an Episode

1. Go to **Admin → Episodes → Add New Episode**
2. Fill in the required fields:
   - **Anime** (select from dropdown)
   - **Episode Number** (required)
   - **Video File** (720p, required)
   - **Status** (required)
   - **Visibility** (required)
3. Optional fields:
   - Episode title
   - Synopsis
   - Poster and thumbnail images
   - Mongolian subtitles
   - English subtitles
   - Tags (searchable)
   - Scheduled publication date
   - Featured/Premium status

## File Upload Guidelines

### Video Files
- **Formats**: MP4, AVI, MOV, WMV
- **Max Size**: 2GB
- **Quality**: 720p (field name indicates quality)
- **Upload Progress**: Real-time progress indicator

### Images
- **Formats**: PNG, JPG, GIF
- **Cover Images**: Up to 2MB
- **Poster Images**: Up to 5MB
- **Thumbnails**: Up to 2MB

### Subtitles
- **Formats**: VTT, SRT
- **Languages**: Mongolian (required), English (optional)
- **Max Size**: Standard text file limits

## Search & Filtering

### Anime Search
- Search by title (all language variants)
- Filter by status
- Filter by category
- Search by ID

### Episode Search  
- Search by episode title
- Search by episode number
- Search by anime name
- Filter by status
- Filter by specific anime
- Search by ID

### Tag Management
- Searchable tag selection
- Live filtering as you type
- Support for both English and Mongolian tag names

## Scheduling System

### Schedule Episodes
1. Set status to "Scheduled"
2. Set the "Scheduled At" date/time
3. Episodes will automatically become available at the scheduled time

### Status Workflow
- **Draft**: Work in progress, not visible
- **Scheduled**: Ready to publish at specified time
- **Published**: Live and available to users
- **Archived**: No longer active but preserved

## Tips for Efficient Workflow

### Recommended Workflow
1. **Create Anime First**: Always create the anime entry before adding episodes
2. **Use Tags**: Tag your content for better organization and discovery
3. **Upload in Batches**: Process multiple episodes of the same anime together
4. **Schedule Releases**: Use scheduling for consistent release patterns

### Best Practices
- **Consistent Naming**: Use consistent episode numbering and naming
- **Quality Images**: Use high-quality poster and thumbnail images
- **Subtitle Quality**: Ensure subtitles are properly synced and formatted
- **Tag Consistency**: Use consistent tags across similar content

## Technical Features

### Upload Progress
- Real-time upload progress for video files
- Visual progress bar with percentage
- Prevents form submission during upload

### Image Previews
- Instant preview of uploaded images
- Current image display during editing
- Visual feedback for better UX

### Responsive Design
- Works on desktop, tablet, and mobile
- Dark mode support
- Modern Tailwind CSS styling

### Data Validation
- Server-side validation for all inputs
- File type and size restrictions
- Required field enforcement

## Troubleshooting

### Common Issues

**Upload Fails**
- Check file size limits
- Verify file format compatibility
- Ensure stable internet connection

**Images Not Displaying**
- Verify storage symlink is created: `php artisan storage:link`
- Check file permissions on storage directory

**Scheduling Not Working**
- Ensure your server timezone is configured correctly
- Check that scheduled tasks are running (if using job queues)

### File Size Limits
If you need to upload larger files, you may need to adjust:
- `upload_max_filesize` in php.ini
- `post_max_size` in php.ini
- `max_execution_time` for large uploads

## Security Considerations

### Access Control
- Currently allows all authenticated users
- Recommend implementing role-based access control
- Consider adding admin-specific middleware

### File Security
- All uploads go through Laravel's validation
- Files stored in secure storage directories
- Consider implementing virus scanning for uploads

## Support

For technical issues or feature requests related to the admin dashboard, please refer to your development team or system administrator.