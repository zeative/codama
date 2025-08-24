"use client";

import React from 'react'
import { SessionProvider } from 'next-auth/react';
import { SidebarProvider } from './SidebarContext';

const SessionContext: React.FC<{ children: React.ReactNode }> = ({ children }) => {
    return (
        <SessionProvider>
            <SidebarProvider>
                {children}
            </SidebarProvider>
        </SessionProvider>
    )
}

export default SessionContext