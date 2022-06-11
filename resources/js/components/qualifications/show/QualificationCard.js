import React from 'react'
import { Card, Col, Button } from 'react-bootstrap'
import { useDispatch } from 'react-redux'
import { deleteQualificationsData } from '../../../features/qualifications/qualificationSlice'
import { FaRegTrashAlt, FaRegEdit } from 'react-icons/fa'

function Qualification(props) {
  const dispatch = useDispatch()
  return (
    <>
      <Col md="auto">
        <Card style={{ width: '18rem', margin: '0.3rem' }}>
          <Card.Body>
            <Card.Title>{props.qualificationData.description}</Card.Title>
            <Card.Subtitle className="mb-2 text-muted">
              ID: {props.qualificationData.id}
            </Card.Subtitle>
            <Button
              href={`/qualification/edit/${props.qualificationData.id}`}
              variant="outline-secondary"
              size="sm"
            >
              <FaRegEdit />
            </Button>{' '}
            <Button
              onClick={() =>
                dispatch(deleteQualificationsData(props.qualificationData.id))
              }
              variant="outline-danger"
              size="sm"
            >
              <FaRegTrashAlt />
            </Button>{' '}
          </Card.Body>
        </Card>
      </Col>
    </>
  )
}

export default Qualification
