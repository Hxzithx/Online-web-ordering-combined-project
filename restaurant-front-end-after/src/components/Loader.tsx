import React from 'react';

interface LoaderProps {
  text?: string;
  showSkeletons?: boolean;
  skeletonType?: 'grid' | 'list';
  count?: number;
}

export const Loader: React.FC<LoaderProps> = ({
  text = 'Loading gourmet menu...',
  showSkeletons = true,
  skeletonType = 'grid',
  count = 6,
}) => {
  return (
    <div className="granny-loader-container animate-fade-in-scale">
      {/* Animated Gold Ring Spinner */}
      <div className="granny-spinner-ring">
        <i className="fa fa-cutlery granny-loader-icon"></i>
      </div>

      {/* Optional Skeleton Grid/List */}
      {showSkeletons && (
        <div className="row" style={{ width: '100%', marginTop: '30px' }}>
          {Array.from({ length: count }).map((_, idx) => (
            <div
              key={idx}
              className={
                skeletonType === 'grid'
                  ? 'col-xs-12 col-sm-6 col-md-4 mb-30'
                  : 'col-xs-12 col-sm-6 col-md-6 mb-30'
              }
            >
              {skeletonType === 'grid' ? (
                <div className="skeleton-card">
                  <div className="skeleton-shimmer skeleton-img" style={{ marginBottom: '15px' }}></div>
                  <div className="skeleton-shimmer skeleton-line short" style={{ margin: '0 auto 10px' }}></div>
                  <div className="skeleton-shimmer skeleton-line medium" style={{ margin: '0 auto 10px' }}></div>
                  <div className="skeleton-shimmer skeleton-line short" style={{ margin: '0 auto' }}></div>
                </div>
              ) : (
                <div className="skeleton-card" style={{ display: 'flex', gap: '15px', alignItems: 'center' }}>
                  <div className="skeleton-shimmer skeleton-img-thumb"></div>
                  <div style={{ flexGrow: 1 }}>
                    <div className="skeleton-shimmer skeleton-line medium" style={{ marginBottom: '10px' }}></div>
                    <div className="skeleton-shimmer skeleton-line full" style={{ marginBottom: '10px' }}></div>
                    <div className="skeleton-shimmer skeleton-line short"></div>
                  </div>
                </div>
              )}
            </div>
          ))}
        </div>
      )}
    </div>
  );
};

export default Loader;
