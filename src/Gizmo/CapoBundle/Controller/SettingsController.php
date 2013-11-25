<?php
/*
    Capo, a web interface for querying multiple Cacti instances
    Copyright (C) 2013  Jochem Kossen <jochem@jkossen.nl>

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

namespace Gizmo\CapoBundle\Controller;

/**
 * Capo Settings controller
 *
 * This class provides an interface for changing settings and administrating
 * users and cacti instances
 *
 * @author Jochem Kossen <jochem@jkossen.nl>
 */
class SettingsController extends BaseController
{
    /**
     * The default settings page
     */
    public function indexAction()
    {
        return $this->savedSelectionsAction();
    }
    
    /**
     * Page for editing saved selections
     */
    public function savedSelectionsAction()
    {
        $data = $this->_get_privileges();
        $data['active_page'] = 'settings';
        return $this->render('GizmoCapoBundle:Settings:saved_selections.html.twig',
                             array('data' => $data));
    }

    /**
     * Page for adding, removing and editing Cacti instances
     */
    public function cactiInstancesAction()
    {
        $this->_need_admin_privileges();
        $data = $this->_get_privileges();
        $data['active_page'] = 'settings';
        return $this->render('GizmoCapoBundle:Settings:cacti_instances.html.twig',
                             array('data' => $data));
    }

    /**
     * Page for configuring users
     */
    public function userAccessAction()
    {
        $this->_need_admin_privileges();
        $data = $this->_get_privileges();
        $data['active_page'] = 'settings';
        return $this->render('GizmoCapoBundle:Settings:user_access.html.twig',
                             array('data' => $data));
    }

    /**
     * Page for granting and revoking access for groups to Cacti instances
     */
    public function groupAccessAction()
    {
        $this->_need_admin_privileges();
        $data = $this->_get_privileges();
        $data['active_page'] = 'settings';
        return $this->render('GizmoCapoBundle:Settings:group_access.html.twig',
                             array('data' => $data));
    }

    /**
     * Page for viewing the Event Log lines
     */
    public function eventLogAction()
    {
        $this->_need_admin_privileges();
        $data = $this->_get_privileges();
        $data['active_page'] = 'settings';
        return $this->render('GizmoCapoBundle:Settings:event_log.html.twig',
                             array('data' => $data));
    }
}
