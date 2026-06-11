<!doctype html>

<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact" dir="ltr"
    data-theme="theme-default" data-assets-path="{{ asset('assets') }}/" data-template="vertical-menu-template" data-style="light">

<head>
    <title>{{ \App\Helpers\Helper::getCompanyName() }} - @yield('title')</title>
    @include('layouts.meta')
    @include('layouts.css')
    @yield('css')
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->
            @include('layouts.sidebar')
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->

                @include('layouts.header')

                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <div class="container-xxl flex-grow-1 container-p-y">
                        <!-- Back Button -->
                        <div class="mb-3" id="global-back-wrapper">
                            <button id="global-back-btn"
                                style="background:#2d2d3f; color:#fff; border:none; border-radius:50px; padding:5px 16px; font-size:0.8rem; font-weight:600; cursor:pointer; display:inline-flex; align-items:center; gap:6px; transition:background 0.15s;"
                                onmouseover="this.style.background='#1a1a2e'"
                                onmouseout="this.style.background='#2d2d3f'">
                                &#8592; Back
                            </button>
                        </div>
                        <!-- / Back Button -->

                        @yield('content')
                    </div>
                    <!-- Content -->
                    <!-- / Content -->

                    <!-- Footer -->
                    @include('layouts.footer')
                    <!-- / Footer -->

                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>

        <!-- Drag Target Area To SlideIn Menu On Small Screens -->
        <div class="drag-target"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- JS -->
    @include('layouts.script')
    <script>
        function updateTime() {
            fetch('/current-time')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('current-time').textContent = data.time;
                })
                .catch(error => console.error('Error fetching time:', error));
        }
        // Update immediately and every minute
        updateTime();
        setInterval(updateTime, 60000);
    </script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/pusher-js@7.0.3"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.11.1/echo.iife.min.js"></script> --}}
    <script>
        $(document).ready(function() {
            // window.Pusher = Pusher;
            // window.Echo = new Echo({
            //     broadcaster: 'pusher',
            //     key: "{{ env('PUSHER_APP_KEY') }}",
            //     cluster: "{{ env('PUSHER_APP_CLUSTER') }}",
            //     forceTLS: true
            // });

            // window.Echo.private(`notifications.${@php echo auth()->user()->id;@endphp}`)
            // .listen('NotificationEvent', (e) => {
            //     console.log('New Notification:', e);
            //     fetchNotifications(); // Refresh the notification list
            // });

            const notificationDropdown = $('.dropdown-notifications-list ul');
            const markAllAsReadButton = $('.dropdown-notifications-all');
            const notificationBadge = $('.badge-notifications'); // The red notification badge
            const unreadCountBadge = $('.badge-unread-count');

            // Get CSRF Token from Meta Tag
            const csrfToken = $('meta[name="csrf-token"]').attr('content');

            function timeAgo(date) {
                const now = new Date();
                const diffInSeconds = Math.floor((now - new Date(date)) / 1000);
                const diffInMinutes = Math.floor(diffInSeconds / 60);
                const diffInHours = Math.floor(diffInMinutes / 60);
                const diffInDays = Math.floor(diffInHours / 24);
                const diffInWeeks = Math.floor(diffInDays / 7);

                if (diffInSeconds < 60) {
                    return 'just now';
                } else if (diffInMinutes < 60) {
                    return `${diffInMinutes} minute${diffInMinutes > 1 ? 's' : ''} ago`;
                } else if (diffInHours < 24) {
                    return `${diffInHours} hour${diffInHours > 1 ? 's' : ''} ago`;
                } else if (diffInDays < 7) {
                    return `${diffInDays} day${diffInDays > 1 ? 's' : ''} ago`;
                } else if (diffInWeeks < 4) {
                    return `${diffInWeeks} week${diffInWeeks > 1 ? 's' : ''} ago`;
                } else {
                    return new Date(date).toLocaleString(); // fallback to default date
                }
            }
            const notificationViewRoute = @json(route('notification.click', ['id' => '__id__']));
            // Fetch notifications
            function fetchNotifications() {
                $.ajax({
                    url: '/get/notifications',
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        // console.log('response', response);
                        const notifications = response.notifications;
                        notificationDropdown.empty(); // Clear existing notifications

                        let unreadCount = response.unreadNotificationsCount;

                        if (notifications.length === 0) {
                            notificationDropdown.append(
                                '<li class="list-group-item text-center text-muted">No notifications</li>'
                            );
                        } else {
                            $.each(notifications, function(index, notification) {
                                // if (!notification.read_at) {
                                //     unreadCount++; // Count unread notifications
                                // }
                                const link = notification.table_name !== null ?
                                    notificationViewRoute.replace('__id__', notification.id) :
                                    'javascript:void(0)';

                                const notificationItem = `
                                    <li class="list-group-item list-group-item-action dropdown-notifications-item ${notification.read_at ? 'marked-as-read' : ''}">
                                        <div class="d-flex">
                                            <a href="${link}">
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1 small">${notification.message}</h6>
                                                    <small class="text-muted">${timeAgo(notification.created_at)}</small>
                                                </div>
                                            </a>
                                            <div class="flex-shrink-0 dropdown-notifications-actions">
                                                <a href="javascript:void(0)" class="dropdown-notifications-read" data-id="${notification.id}">
                                                    <span class="badge badge-dot"></span>
                                                </a>
                                                <a href="javascript:void(0)" class="dropdown-notifications-archive" data-id="${notification.id}">
                                                    <span class="ti ti-x"></span>
                                                </a>
                                            </div>
                                        </div>
                                    </li>
                                `;
                                notificationDropdown.append(notificationItem);

                            });
                        }

                        // Hide or show the red badge based on unread count
                        if (unreadCount > 0) {
                            notificationBadge.show();
                            unreadCountBadge.text(`${unreadCount} New`).show();
                        } else {
                            notificationBadge.hide();
                            unreadCountBadge.hide();
                        }
                    },
                    error: function(error) {
                        console.error('Error fetching notifications:', error);
                    }
                });
            }

            // Mark a notification as read
            notificationDropdown.on('click', '.dropdown-notifications-read', function() {
                const notificationId = $(this).data('id');
                $.ajax({
                    url: `/notifications/${notificationId}/mark-as-read`,
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function() {
                        fetchNotifications();
                    },
                    error: function(error) {
                        console.error('Error marking notification as read:', error);
                    }
                });
            });

            // Mark all notifications as read
            markAllAsReadButton.on('click', function() {
                $.ajax({
                    url: '/notifications/mark-all-as-read',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function() {
                        fetchNotifications();
                    },
                    error: function(error) {
                        console.error('Error marking all notifications as read:', error);
                    }
                });
            });

            // Archive (delete) a notification
            notificationDropdown.on('click', '.dropdown-notifications-archive', function() {
                const notificationId = $(this).data('id');
                $.ajax({
                    url: `/notifications/${notificationId}/delete`, // Change the endpoint to your delete API
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function() {
                        fetchNotifications();
                    },
                    error: function(error) {
                        console.error('Error deleting notification:', error);
                    }
                });
            });

            // Fetch notifications on page load
            fetchNotifications();
        });
    </script>
    <script>
    (function () {
        var path = window.location.pathname.replace(/\/$/, '');
        var wrapper = document.getElementById('global-back-wrapper');
        var btn     = document.getElementById('global-back-btn');
        if (!wrapper || !btn) return;

        // Hide on dashboard home
        if (path === '/dashboard' || path === '' || path === '/') {
            wrapper.style.display = 'none';
            return;
        }

        var segments = path.replace(/^\//, '').split('/').filter(Boolean);
        var backUrl;

        if (segments.length <= 1) {
            // /profile, /settings etc. → dashboard
            backUrl = '/dashboard';
        } else if (segments[0] === 'dashboard' && segments.length === 2) {
            // /dashboard/cases, /dashboard/billings, /dashboard/payments → dashboard
            backUrl = '/dashboard';
        } else if (segments[0] === 'dashboard' && segments.length >= 3) {
            // /dashboard/cases/5, /dashboard/cases/5/edit → /dashboard/cases
            backUrl = '/' + segments[0] + '/' + segments[1];
        } else {
            backUrl = '/dashboard';
        }

        btn.addEventListener('click', function () {
            window.location.href = backUrl;
        });
    })();
    </script>
</body>

</html>
