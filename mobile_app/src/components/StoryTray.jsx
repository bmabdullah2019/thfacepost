import React, { useRef } from 'react';
import { Plus } from 'lucide-react';

export default function StoryTray({ stories, currentUser, onSelectStory, onAddStory }) {
  const storyFileInputRef = useRef(null);

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
            src={currentUser.avatar}
            alt="My Avatar"
            className="create-story-top-img"
            onError={(e) => { e.target.src = 'https://thefacepost.com/themes/flavor/images/user-red.png'; }}
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
              onError={(e) => { e.target.src = 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?w=800&auto=format&fit=crop&q=80'; }}
            />
            <div className="story-gradient" />
            
            <div className={`story-user-badge ${story.unread ? '' : 'read'}`}>
              <img
                src={story.user.avatar}
                alt={story.user.name}
                className="story-user-img"
                onError={(e) => { e.target.src = 'https://thefacepost.com/themes/flavor/images/user-red.png'; }}
              />
            </div>

            <span className="story-name">{story.user.name}</span>
          </div>
        ))}
      </div>
    </section>
  );
}
