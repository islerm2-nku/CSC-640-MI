# ...existing code...
from irsdk import IBT # name will differ by package
from itertools import groupby

ibt = IBT()
ibt.open(ibt_file='')

laps = ibt.get_all('Lap')
gear = ibt.get_all('Gear')

def ranges_groupby(a):
    return [
        (value, indices[0], indices[-1])
        for value, group in groupby(enumerate(a), key=lambda iv: iv[1])
        for indices in [[i for i, _ in group]]
    ]
# ...existing code...

def ranges_groupby_with_offset(a, offset=0):
    """
    Like ranges_groupby but returns indices offset by `offset`.
    Use this to get absolute frame indices when slicing arrays.
    """
    return [
        (value, indices[0] + offset, indices[-1] + offset)
        for value, group in groupby(enumerate(a), key=lambda iv: iv[1])
        for indices in [[i for i, _ in group]]
    ]

def lap_ranges():
    """Return list of (lap_value, start_frame, end_frame)."""
    return ranges_groupby(laps)

def gear_ranges_for_lap(lap_value):
    """
    Return list of (gear_value, start_frame, end_frame) that occur within the frames
    of the given lap_value. If lap not found returns empty list.
    """
    for val, start, end in lap_ranges():
        if val == lap_value:
            return ranges_groupby_with_offset(gear[start:end+1], offset=start)
    return []

# Example: print all lap ranges and show gears for lap 5 (change lap_to_show as needed)
lap_to_show = 5
print(f'laps: {lap_ranges()}')
print(f'gear ranges for lap {lap_to_show}: {gear_ranges_for_lap(lap_to_show)}')

#gets total number of records in the ibt file
total_records = ibt._disk_header.session_record_count
speed = [{}]
for i in range(total_records):
    speed.append({'speed':ibt.get(i, 'Speed'), 'throttle':ibt.get(i, 'Throttle')})

print('done')
