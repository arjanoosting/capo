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

namespace Gizmo\CapoBundle\Entity;

class GraphTemplateRepository extends BaseEntityRepository
{
    /**
     * Get array of graph templates
     *
     * @param Array $data array of filters
     *
     * @return Array array of graph templates
     */
    public function getGraphTemplates($data, $as_array = false)
    {
        $qb = $this->_getStdQueryBuilder($data);
        $q = $qb['q'];

        $q->select('distinct(e.name)');
        $q->join('e.cacti_instance', 'c');

        $q->where('1 = 1');

        $this->_access_control($q, $data);

        if (isset($data['cacti_instance'])) {
            $q->andWhere('e.cacti_instance = :cacti_instance');
            $q->setParameter('cacti_instance', $data['cacti_instance']);
        } else {
            if (! (isset($data['active_cacti_only']) && intval($data['active_cacti_only']) === 0) ) {
                $q->andWhere('c.active = 1');
            }
        }

        if (isset($data['q'])) {
            $q->andWhere('e.name LIKE :query');
            $q->setParameter('query', '%' . $data['q'] . '%');
        }

        $q->addOrderBy('e.name', 'ASC');

        $total = $this->_getResultCount($q);

        $q->setFirstResult($qb['first_result']);
        $q->setMaxResults($qb['limit']);

        $query = $q->getQuery();

        $array_result = ($as_array) ? $query->getArrayResult() : $query->getResult();

        return array(
            'graph_templates_total' => $total,
            'graph_templates' => $array_result
        );
    }
}
