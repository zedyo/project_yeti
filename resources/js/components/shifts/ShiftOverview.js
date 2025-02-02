import React from 'react'
import {
  Button,
  Row,
  Container,
  Card,
  Stack,
  Breadcrumb,
} from 'react-bootstrap'
import ShiftCard from './show/ShiftCard'
import { useSelector } from 'react-redux'
import { AiOutlinePlus } from 'react-icons/ai'

function Shifts() {
  const { shiftsData } = useSelector((store) => store.shifts)

  return (
    <>
      <Container style={{ padding: '2rem 0' }}>
        <Breadcrumb>
          <Breadcrumb.Item href="/">Dienstplan</Breadcrumb.Item>
          <Breadcrumb.Item active>Schichten</Breadcrumb.Item>
        </Breadcrumb>
        <Card>
          <Card.Header>
            <Stack direction="horizontal" gap={3}>
              <div>Schichten</div>
              <div className="ms-auto">
                <Button href={`/shift/create`} variant="outline-success">
                  <AiOutlinePlus /> Neue Schicht
                </Button>
              </div>
            </Stack>
          </Card.Header>
          <Card.Body>
            <Container fluid="sm">
              <Row>
                {shiftsData.map((shiftsObject) => (
                  <ShiftCard key={shiftsObject.id} shiftsData={shiftsObject} />
                ))}
              </Row>
            </Container>
          </Card.Body>
        </Card>
      </Container>
    </>
  )
}

export default Shifts
