import React, { useRef } from 'react';
import { Plus } from 'lucide-react';

export default function StoryTray({ stories, currentUser, onSelectStory, onAddStory }) {
  const storyFileInputRef = useRef(null);
  const defaultAvatar = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23cbd5e1'%3E%3Cpath fill-rule='evenodd' d='M7.5 6a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM3.751 20.105a8.25 8.25 0 0116.498 0 .75.75 0 01-.437.695A18.683 18.683 0 0112 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 01-.437-.695z' clip-rule='evenodd'/%3E%3C/svg%3E";

  const handleStoryFileChange = (e) => {
    const file = e.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onloadend = () => {
        onAddStory(reader.result);
      };
      reader.readAsDataURL(file);
    }
  };

  return (
    <section className="story-tray">
      <input 
        type="file" 
        ref={storyFileInputRef} 
        onChange={handleStoryFileChange} 
        accept="image/*" 
        style={{ display: 'none' }} 
      />

      <div className="story-scroll">
        {/* Create Story Card */}
        <div 
          className="create-story-card" 
          onClick={() => storyFileInputRef.current?.click()}
        >
          <img
            src={currentUser.avatar || defaultAvatar}
            alt="My Avatar"
            className="create-story-top-img"
            onError={(e) => { e.target.src = defaultAvatar; }}
          />
          <div className="create-story-bottom">
            <div className="create-story-plus-btn">
              <Plus size={20} strokeWidth={3} />
            </div>
            <span className="create-story-text">Create story</span>
          </div>
        </div>

        {/* Friend Stories */}
        {stories.map((story, index) => (
          <div
            key={story.id}
            className="story-card"
            onClick={() => onSelectStory(index)}
          >
            <img
              src={story.mediaUrl}
              alt="Story"
              className="story-bg-img"
              loading="lazy"
              onError={(e) => { 
                e.target.src = 'https://images.unsplash.com/photo-1579546929518-9e396f3cc809?w=600&auto=format&fit=crop&q=80'; 
              }}
            />
            <div className="story-gradient" />
            
            <div className={`story-user-badge ${story.unread ? '' : 'read'}`}>
              <img
                src={story.user.avatar || defaultAvatar}
                alt={story.user.name}
                className="story-user-img"
                onError={(e) => { e.target.src = defaultAvatar; }}
              />
            </div>

            <span className="story-name">{story.user.name}</span>
          </div>
        ))}
      </div>
    </section>
  );
}
