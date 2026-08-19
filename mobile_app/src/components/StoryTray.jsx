import React from 'react';
import { Plus } from 'lucide-react';

export default function StoryTray({ stories, currentUser, onSelectStory, onAddStory }) {
  return (
    <section className="story-tray">
      <div className="story-scroll">
        {/* Create Story Card */}
        <div className="create-story-card" onClick={onAddStory}>
          <img
            src={currentUser.avatar}
            alt="My Avatar"
            className="create-story-top-img"
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
            />
            <div className="story-gradient" />
            
            <div className={`story-user-badge ${story.unread ? '' : 'read'}`}>
              <img
                src={story.user.avatar}
                alt={story.user.name}
                className="story-user-img"
              />
            </div>

            <span className="story-name">{story.user.name}</span>
          </div>
        ))}
      </div>
    </section>
  );
}
