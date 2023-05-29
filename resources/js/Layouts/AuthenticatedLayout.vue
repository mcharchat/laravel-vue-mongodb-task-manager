<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';


//function that generates a random color for the user avatar, based on the name
function stringToColour(str) {
    var hash = 0;
    for (var i = 0; i < str.length; i++) {
        hash = str.charCodeAt(i) + ((hash << 5) - hash);
    }
    var colour = '#';
    for (var i = 0; i < 3; i++) {
        var value = (hash >> (i * 8)) & 0xFF;
        colour += ('00' + value.toString(16)).substr(-2);
    }
    return colour;
}

//function that verify if screen is mobile
function isMobile() {
    return window.innerWidth < 768;
}
// ref  if slim navigation is active
const slimNavigation = ref(isMobile());
</script>

<template>
    <div>
        <div class="flex h-full">
            <!-- Side Menu -->
            <div :class="{
                'transition-all bg-white dark:bg-gray-800 h-screen fixed': true,
                'w-0 -left-64': slimNavigation && isMobile(),
                'w-12': slimNavigation && !isMobile(),
                'z-20': !slimNavigation && isMobile(),
                'w-64': !slimNavigation
            }">
                <div class="flex flex-col justify-between h-screen shadow">
                    <div>
                        <!-- Logo -->
                        <div class="flex items-center justify-center h-16">

                            <!-- Hamburger -->
                            <Link :href="route('dashboard')" class="flex flex-grow justify-center">
                                <ApplicationLogo
                                    class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200"
                                />
                            </Link>
                        </div>
                
                        <!-- Navigation Links -->
                        <div class="py-4 space-y-1 flex flex-col">
                            <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')" class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 1024 1024" :class="{'mr-2': !slimNavigation, 'transition-all': true}"><path fill="currentColor" d="M924.8 385.6a446.7 446.7 0 0 0-96-142.4a446.7 446.7 0 0 0-142.4-96C631.1 123.8 572.5 112 512 112s-119.1 11.8-174.4 35.2a446.7 446.7 0 0 0-142.4 96a446.7 446.7 0 0 0-96 142.4C75.8 440.9 64 499.5 64 560c0 132.7 58.3 257.7 159.9 343.1l1.7 1.4c5.8 4.8 13.1 7.5 20.6 7.5h531.7c7.5 0 14.8-2.7 20.6-7.5l1.7-1.4C901.7 817.7 960 692.7 960 560c0-60.5-11.9-119.1-35.2-174.4zM761.4 836H262.6A371.12 371.12 0 0 1 140 560c0-99.4 38.7-192.8 109-263c70.3-70.3 163.7-109 263-109c99.4 0 192.8 38.7 263 109c70.3 70.3 109 163.7 109 263c0 105.6-44.5 205.5-122.6 276zM623.5 421.5a8.03 8.03 0 0 0-11.3 0L527.7 506c-18.7-5-39.4-.2-54.1 14.5a55.95 55.95 0 0 0 0 79.2a55.95 55.95 0 0 0 79.2 0a55.87 55.87 0 0 0 14.5-54.1l84.5-84.5c3.1-3.1 3.1-8.2 0-11.3l-28.3-28.3zM490 320h44c4.4 0 8-3.6 8-8v-80c0-4.4-3.6-8-8-8h-44c-4.4 0-8 3.6-8 8v80c0 4.4 3.6 8 8 8zm260 218v44c0 4.4 3.6 8 8 8h80c4.4 0 8-3.6 8-8v-44c0-4.4-3.6-8-8-8h-80c-4.4 0-8 3.6-8 8zm12.7-197.2l-31.1-31.1a8.03 8.03 0 0 0-11.3 0l-56.6 56.6a8.03 8.03 0 0 0 0 11.3l31.1 31.1c3.1 3.1 8.2 3.1 11.3 0l56.6-56.6c3.1-3.1 3.1-8.2 0-11.3zm-458.6-31.1a8.03 8.03 0 0 0-11.3 0l-31.1 31.1a8.03 8.03 0 0 0 0 11.3l56.6 56.6c3.1 3.1 8.2 3.1 11.3 0l31.1-31.1c3.1-3.1 3.1-8.2 0-11.3l-56.6-56.6zM262 530h-80c-4.4 0-8 3.6-8 8v44c0 4.4 3.6 8 8 8h80c4.4 0 8-3.6 8-8v-44c0-4.4-3.6-8-8-8z"/></svg>
                                <span :class="{
                                    'ml-2': !slimNavigation,
                                    'hidden': slimNavigation,
                                    'transition-all': true
                                }">Dashboard</span>
                            </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('projects')" :active="route().current('projects')" class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 1024 1024" :class="{'mr-2': !slimNavigation, 'transition-all': true}"><path fill="currentColor" d="M280 752h80c4.4 0 8-3.6 8-8V280c0-4.4-3.6-8-8-8h-80c-4.4 0-8 3.6-8 8v464c0 4.4 3.6 8 8 8zm192-280h80c4.4 0 8-3.6 8-8V280c0-4.4-3.6-8-8-8h-80c-4.4 0-8 3.6-8 8v184c0 4.4 3.6 8 8 8zm192 72h80c4.4 0 8-3.6 8-8V280c0-4.4-3.6-8-8-8h-80c-4.4 0-8 3.6-8 8v256c0 4.4 3.6 8 8 8zm216-432H144c-17.7 0-32 14.3-32 32v736c0 17.7 14.3 32 32 32h736c17.7 0 32-14.3 32-32V144c0-17.7-14.3-32-32-32zm-40 728H184V184h656v656z"/></svg>
                                <span :class="{
                                    'ml-2': !slimNavigation,
                                    'hidden': slimNavigation,
                                    'transition-all': true
                                }">Projects</span>
                            </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('tasks')" :active="route().current('tasks')" class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" :class="{'mr-2': !slimNavigation, 'transition-all': true}"><path fill="currentColor" d="M22 5.18L10.59 16.6l-4.24-4.24l1.41-1.41l2.83 2.83l10-10L22 5.18zm-2.21 5.04c.13.57.21 1.17.21 1.78c0 4.42-3.58 8-8 8s-8-3.58-8-8s3.58-8 8-8c1.58 0 3.04.46 4.28 1.25l1.44-1.44A9.9 9.9 0 0 0 12 2C6.48 2 2 6.48 2 12s4.48 10 10 10s10-4.48 10-10c0-1.19-.22-2.33-.6-3.39l-1.61 1.61z"/></svg>
                                <span :class="{
                                    'ml-2': !slimNavigation,
                                    'hidden': slimNavigation,
                                    'transition-all': true
                                }">Tasks</span>
                            </ResponsiveNavLink>
                        </div>
                    </div>
                    <div>
                        <!-- Settings Dropdown -->
                        <div class="flex items-center justify-center">
                            <!-- Settings Dropdown -->
                            <div class="relative">
                                <Dropdown align="bla" width="48">
                                    <template #trigger>
                                        <div class="cursor-pointer flex flex-row items-center justify-center space-x-4 p-2 border-t-2">
                                            <div class="inline-block rounded-full h-8 w-8 text-white flex items-center justify-center text-xl font-bold" :style="{ backgroundColor: stringToColour($page.props.auth.user.name) }">
                                                {{ $page.props.auth.user.name.charAt(0) }}
                                            </div>
                                            <div :class="{
                                                'flex flex-col space-y-0.5 transition-all': true,
                                                'hidden': slimNavigation
                                            }">
                                                <span class="text-base font-medium text-gray-600">
                                                    {{ $page.props.auth.user.name }}
                                                </span>
                                                <span class="text-sm font-light text-gray-500">
                                                    {{ $page.props.auth.user.email }}
                                                </span>
                                            </div>
                                        </div>
                                    </template>

                                    <template #content>
                                        <span class="block w-full px-4 text-center">
                                            Hi {{ $page.props.auth.user.name }}!
                                        </span>
                                        <DropdownLink :href="route('profile.edit')"> Profile </DropdownLink>
                                        <DropdownLink :href="route('logout')" method="post" as="button">
                                            Log Out
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>
                    </div>
                </div> 
            </div>
            <!-- Overlay -->
            <div :class="{
                'transition-all fixed inset-0 bg-black': true,
                'opacity-25 z-10': !slimNavigation && isMobile(),
                'opacity-0 z-0': slimNavigation && isMobile(),
                'hidden': !isMobile()
             }" @click="slimNavigation = !slimNavigation"></div>
            <!-- Main Content -->
            <div :class="{
                'transition-all flex-1 bg-gray-100 dark:bg-gray-900 h-screen overflow-auto': true,
                'ml-12': slimNavigation && !isMobile(),
                'ml-64': !slimNavigation && !isMobile(),
            }">
            <!-- Page Heading -->
                <header class="bg-white dark:bg-gray-800 shadow flex place-items-center" v-if="$slots.header">                    
                    <button
                        @click="slimNavigation = !slimNavigation"
                        class="inline-flex items-center justify-center p-2 ml-4 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out"
                    >
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path
                                class="inline-flex"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                fill="currentColor"
                                d="M 4 6 h 16 M 4 12 h 16 M 4 18 h 16"
                            />
                        </svg>
                    </button>
                    <div class="max-w-7xl mr-auto py-6 px-4 sm:px-6 lg:px-8">
                        <slot name="header" />
                    </div>
                </header>

                <!-- Page Content -->
                <main>
                    <slot />
                </main>        
            </div>
        </div>
    </div>
</template>
